<?php
namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Location;
use App\Models\News;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeNews extends Command
{
    protected $signature = 'scrape:news';
    protected $description = 'Scrapea noticias de múltiples fuentes y las categoriza';

    public function handle()
    {
        $urls_diarios = [
            'https://diariosinfronteras.com.pe/category/puno/page/{}',
            'https://elcomercio.pe/noticias/puno/{}/',
            'https://rpp.pe/noticias/page/{}/',
            'https://pachamamaradio.org/page/{}',
            'https://diariocorreo.pe/noticias/puno/{}/',
            'https://losandes.com.pe/category/regional/page/{}'
        ];

        $categories_keywords = [
            'Educación' => ['universidad', 'colegio', 'educación', 'estudiante', 'escuela', 'maestría', 'carrera', 'aprendizaje', 'pedagogía', 'investigación', 'docente', 'profesor', 'beca'],
            'Desarrollo' => ['infraestructura', 'proyecto', 'desarrollo', 'construcción', 'avance', 'innovación', 'inversión', 'economía', 'modernización', 'tecnología', 'urbanización'],
            'Violencia' => ['asesinato', 'robo', 'violencia', 'delincuencia', 'crimen', 'homicidio', 'secuestro', 'pandillas', 'agresión', 'abuso', 'feminicidio', 'narcotráfico'],
            'Medio Ambiente' => ['cambio climático', 'medio ambiente', 'contaminación', 'ecología', 'reciclaje', 'biodiversidad', 'deforestación', 'conservación', 'fauna', 'flora', 'sostenibilidad'],
            'Política' => ['elección', 'gobierno', 'presidente', 'alcalde', 'política', 'ministro', 'congreso', 'ley', 'reforma', 'campaña', 'partido', 'democracia', 'corrupción'],
            'Salud' => ['salud', 'hospital', 'enfermedad', 'vacuna', 'epidemia', 'COVID', 'médico', 'tratamiento', 'cirugía', 'prevención', 'medicina', 'investigación médica'],
            'Cultura' => ['cultura', 'arte', 'música', 'danza', 'tradición', 'patrimonio', 'festival', 'pintura', 'teatro', 'literatura', 'gastronomía', 'exposición'],
            'Deportes' => ['fútbol', 'deporte', 'campeonato', 'torneo', 'equipo', 'jugador', 'entrenador', 'estadio', 'medalla', 'gol', 'competencia', 'entrenamiento', 'olímpico'],
            'Economía' => ['economía', 'mercado', 'finanzas', 'inflación', 'comercio', 'empleo', 'inversión', 'crisis', 'exportación', 'importación', 'emprendimiento', 'banca'],
            'Sociedad' => ['sociedad', 'comunidad', 'igualdad', 'derechos', 'inclusión', 'justicia', 'minorías', 'género', 'familia', 'protesta', 'solidaridad', 'voluntariado'],
            'Tecnología' => ['tecnología', 'innovación', 'ciencia', 'internet', 'software', 'hardware', 'inteligencia artificial', 'robótica', 'programación', 'ciberseguridad', 'blockchain'],
            'Turismo' => ['turismo', 'viaje', 'hotel', 'reserva', 'destino', 'guía', 'cultura turística', 'paquete turístico', 'patrimonio', 'aventura', 'naturaleza'],
            'Agricultura' => ['agricultura', 'cultivo', 'cosecha', 'campo', 'riego', 'ganadería', 'fertilizante', 'agroindustria', 'exportación agrícola', 'plagas', 'granja', 'suelo'],
        ];

        foreach ($urls_diarios as $base_url) {
            for ($page = 1; $page <= 10; $page++) {
                $url = str_replace('{}', $page, $base_url);
                $this->info("Procesando URL: $url");

                try {
                    $response = Http::get($url);
                    $crawler = new Crawler($response->body());

                    $news_items = $crawler->filter('h3.entry-title'); // Ajusta según el sitio web

                    $news_items->each(function (Crawler $node) use ($categories_keywords) {
                        $title = $node->filter('a')->attr('title') ?? 'Título no disponible';
                        $url = $node->filter('a')->attr('href') ?? 'Enlace no disponible';

                        // Extraer fecha de la URL
                        preg_match('/(\d{4})\/(\d{2})\/(\d{2})/', $url, $matches);
                        $date = $matches ? "{$matches[1]}-{$matches[2]}-{$matches[3]}" : null;

                        // Obtener contenido e imagen
                        try {
                            $news_response = Http::get($url);
                            $news_crawler = new Crawler($news_response->body());
                            $content = $news_crawler->filter('div.post-content-bd')->text() ?? 'Contenido no disponible';
                            $image = $news_crawler->filter('img')->attr('src') ?? null;
                        } catch (\Exception $e) {
                            $content = 'Contenido no disponible';
                            $image = null;
                        }

                        // Categorización basada en palabras clave
                        $category_name = 'Sin Categoría';
                        foreach ($categories_keywords as $category => $keywords) {
                            foreach ($keywords as $keyword) {
                                if (stripos($title, $keyword) !== false || stripos($content, $keyword) !== false) {
                                    $category_name = $category;
                                    break 2; // Salir del bucle si encontramos una coincidencia
                                }
                            }
                        }

                        // Verificar o crear la categoría
                        // Obtener o crear la categoría
                        $category = Category::firstOrCreate(['name' => $category_name]);

                        // Guardar la noticia con la categoría asociada
                        News::updateOrCreate(
                            ['url' => $url],
                            [
                                'title' => $title,
                                'content' => $content,
                                'image' => $image,
                                'date' => $date,
                                'location_id' => 1, // Asigna la ubicación que corresponda
                                'category_id' => $category->id, // Relación con la categoría
                            ]
                        );


                        $this->info("Noticia guardada: $title, Categoría: $category_name");
                    });
                } catch (\Exception $e) {
                    $this->error("Error al procesar la URL $url: " . $e->getMessage());
                }
            }
        }

        $this->info("Scraping completado.");
    }
}
