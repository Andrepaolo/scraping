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
            'color' => '#34D399',
        ]);
        Category::create([
            'name' => 'Desarrollo',
            'color' => '#60A5FA',
        ]);
        Category::create([
            'name' => 'Violencia',
            'color' => '#F87171',
        ]);
        Category::create([
            'name' => 'Medio Ambiente',
            'color' => '#22D3EE',
        ]);
        Category::create([
            'name' => 'Política',
            'color' => '#9333EA',
        ]);
        Category::create([
            'name' => 'Cultura',
            'color' => '#E879F9',
        ]);
        Category::create([
            'name' => 'Deportes',
            'color' => '#10B981',
        ]);
        Category::create([
            'name' => 'Economía',
            'color' => '#F43F5E',
        ]);
        Category::create([
            'name' => 'Sociedad',
            'color' => '#6B7280',
        ]);
        Category::create([
            'name' => 'Tecnología',
            'color' => '#A78BFA',
        ]);
        Category::create([
            'name' => 'Turismo',
            'color' => '#FBBF24',
        ]);
        Category::create([
            'name' => 'Agricultura',
            'color' => '#4ADE80',
        ]);
        Category::create([
            'name' => 'Salud',
            'color' => '#38BDF8',
        ]);
        Category::create([
            'name' => 'Sin Categoría',
            'color' => '#D1D5DB',
        ]);
        Location::create([
            'name' => 'Puno',
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
            'name' => 'Amazonas',
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
