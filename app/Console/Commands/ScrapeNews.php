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

        $locations_keywords = [
            'Puno' => ['Puno', 'Juliaca', 'Titicaca', 'Altiplano'],
            'Cusco' => ['Cusco', 'Machu Picchu', 'Urubamba', 'Valle Sagrado'],
            'Lima' => ['Lima', 'Callao', 'Miraflores', 'San Isidro'],
            'Arequipa' => ['Arequipa', 'Colca Canyon', 'El Misti', 'Yanahuara', 'Santa Catalina', 'Valle de los Volcanes'],
            'Tacna' => ['Tacna', 'Arica', 'Inti Punku', 'Cerro Morro', 'Plaza Zela'],
            'Moquegua' => ['Moquegua', 'Ilo', 'Cerro Baul', 'La Yarada', 'Laguna de Moquegua'],
            'Apurímac' => ['Abancay', 'Aymaraes', 'Sayhuite', 'Chincheros'],
            'Ayacucho' => ['Ayacucho', 'Huanta', 'Vilcashuamán', 'Catedral de Ayacucho', 'Wari'],
            'Huancavelica' => ['Huancavelica', 'Tayacaja', 'Condores', 'Los Morochucos'],
            'Ica' => ['Ica', 'Nazca', 'Huacachina', 'Oasis de Huacachina', 'Reserva Nacional de Paracas'],
            'Apurímac' => ['Abancay', 'Chincheros', 'Sayhuite'],
            'Arequipa' => ['Arequipa', 'Colca Canyon', 'Valle del Colca', 'Cañón de los Andes', 'Cerro Misti'],
            'Puno' => ['Puno', 'Lago Titicaca', 'Juliaca', 'Isla de los Uros', 'Chucuito'],
            'Moquegua' => ['Moquegua', 'Ilo', 'Cerro Baul', 'Laguna de Moquegua'],
            'Tacna' => ['Tacna', 'Arica', 'Cerro Morro', 'Plaza Zela'],
            'Loreto' => ['Iquitos', 'Amazonas', 'Puerto Maldonado', 'Rio Amazonas', 'Reserva Nacional Pacaya Samiria'],
            'Madre de Dios' => ['Puerto Maldonado', 'Madre de Dios', 'Boca Manu', 'Tambopata'],
            'Junín' => ['Junín', 'Huancayo', 'Chanchamayo', 'Valle del Mantaro', 'Reserva de Oxapampa'],
                'Huánuco' => ['Huánuco', 'Tingo María', 'Aparicio Pomares', 'Catarata de Tingo María'],
                'San Martín' => ['Tarapoto', 'Chazuta', 'Laguna Sauce', 'Rio Huallaga', 'Aguas Blancas'],


            // Agrega más departamentos con sus palabras clave relacionadas
        ];

        foreach ($urls_diarios as $base_url) {
            for ($page = 1; $page <= 30; $page++) {
                $url = str_replace('{}', $page, $base_url);
                $this->info("Procesando URL: $url");

                try {
                    $response = Http::get($url);
                    if ($response->failed()) {
                        $this->error("Error al obtener el contenido de la URL: $url");
                        continue;
                    }

                    $crawler = new Crawler($response->body());
                    $news_items = $crawler->filter('h3.entry-title'); // Ajusta este selector según el sitio web

                    $news_items->each(function (Crawler $node) use ($categories_keywords, $locations_keywords) {
                        $title = $node->filter('a')->attr('title') ?? 'Título no disponible';
                        $url = $node->filter('a')->attr('href') ?? 'Enlace no disponible';

                        // Extraer fecha
                        $date = $this->extractDateFromUrlOrContent($url);

                        // Obtener contenido e imagen
                        $content = $this->fetchContent($url, 'div.post-content-bd'); // Ajusta el selector
                        $image = $this->fetchImage($url, 'div.featured-image img'); // Ajusta el selector

                        // Categorizar
                        $category_name = $this->categorizeContent($title, $content, $categories_keywords);
                        $category = Category::firstOrCreate(['name' => $category_name]);

                        // Identificar ubicación
                        $location_name = $this->identifyLocation($title, $content, $locations_keywords);
                        $location = Location::where('name', $location_name)->first();

                        // Guardar noticia
                        News::updateOrCreate(
                            ['url' => $url],
                            [
                                'title' => $title,
                                'content' => $content,
                                'image' => $image,
                                'date' => $date,
                                'location_id' => $location?->id,
                                'category_id' => $category->id,
                            ]
                        );

                        $this->info("Noticia guardada: $title, Categoría: $category_name, Ubicación: $location_name");
                    });
                } catch (\Exception $e) {
                    $this->error("Error al procesar la URL $url: " . $e->getMessage());
                }
            }
        }

        $this->info("Scraping completado.");
    }

    private function extractDateFromUrlOrContent($url)
    {
        preg_match('/(\d{4})\/(\d{2})\/(\d{2})/', $url, $matches);
        return $matches ? "{$matches[1]}-{$matches[2]}-{$matches[3]}" : null;
    }

    private function fetchContent($url, $selector)
    {
        try {
            $response = Http::get($url);
            $crawler = new Crawler($response->body());
            $content = $crawler->filter($selector)->html();
            return strip_tags($content, '<p><a><b><i><ul><ol><li>');
        } catch (\Exception $e) {
            $this->error("Error al obtener contenido de $url: " . $e->getMessage());
            return 'Contenido no disponible';
        }
    }

    private function fetchImage($url, $selector)
    {
        try {
            $response = Http::get($url);
            $crawler = new Crawler($response->body());
            $image = $crawler->filter($selector)->attr('src');
            return filter_var($image, FILTER_VALIDATE_URL) ? $image : url($image);
        } catch (\Exception $e) {
            $this->error("Error al obtener imagen de $url: " . $e->getMessage());
            return null;
        }
    }

    private function categorizeContent($title, $content, $categories_keywords)
    {
        foreach ($categories_keywords as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($title, $keyword) !== false || stripos($content, $keyword) !== false) {
                    return $category;
                }
            }
        }
        return 'Sin Categoría';
    }

    private function identifyLocation($title, $content, $locations_keywords)
    {
        foreach ($locations_keywords as $location => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($title, $keyword) !== false || stripos($content, $keyword) !== false) {
                    return $location;
                }
            }
        }
        return 'Desconocido';
    }
}
