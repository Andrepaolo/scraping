<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Location;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $categories = [
            'Educación',
            'Desarrollo',
            'Violencia',
            'Medio Ambiente',
            'Política',
            'Salud',
            'Cultura',
            'Deportes',
            'Economía',
            'Sociedad',
            'Tecnología',
            'Turismo',
            'Agricultura',
        ];

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        Category::create([
            'name' => 'Educación',
        ]);
        Category::create([
            'name' => 'Desarrollo',
        ]);
        Category::create([
            'name' => 'Violencia',
        ]);
        Category::create([
            'name' => 'Medio Ambiente',
        ]);
        Category::create([
            'name' => 'Política',
        ]);
        Category::create([
            'name' => 'Cultura',
        ]);
        Category::create([
            'name' => 'Deportes',
        ]);
        Category::create([
            'name' => 'Economía',
        ]);
        Category::create([
            'name' => 'Sociedad',
        ]);
        Category::create([
            'name' => 'Tecnología',
        ]);
        Category::create([
            'name' => 'Turismo',
        ]);
        Category::create([
            'name' => 'Agricultura',
        ]);
        Location::create([
            'name' => 'Amazonas',
        ]);
        Location::create([
            'name' => 'Áncash',
        ]);
        Location::create([
            'name' => 'Apurímac',
        ]);
        Location::create([
            'name' => 'Arequipa',
        ]);
        Location::create([
            'name' => 'Ayacucho',
        ]);
        Location::create([
            'name' => 'Cajamarca',
        ]);
        Location::create([
            'name' => 'Callao',
        ]);
        Location::create([
            'name' => 'Cusco',
        ]);
        Location::create([
            'name' => 'Huancavelica',
        ]);
        Location::create([
            'name' => 'Huánuco',
        ]);
        Location::create([
            'name' => 'Ica',
        ]);
        Location::create([
            'name' => 'Junín',
        ]);
        Location::create([
            'name' => 'La Libertad',
        ]);
        Location::create([
            'name' => 'Lambayeque',
        ]);
        Location::create([
            'name' => 'Lima',
        ]);
        Location::create([
            'name' => 'Loreto',
        ]);
        Location::create([
            'name' => 'Madre de Dios',
        ]);
        Location::create([
            'name' => 'Moquegua',
        ]);
        Location::create([
            'name' => 'Pasco',
        ]);
        Location::create([
            'name' => 'Piura',
        ]);
        Location::create([
            'name' => 'Puno',
        ]);
        Location::create([
            'name' => 'San Martín',
        ]);
        Location::create([
            'name' => 'Tacna',
        ]);
        Location::create([
            'name' => 'Tumbes',
        ]);


    }
}
