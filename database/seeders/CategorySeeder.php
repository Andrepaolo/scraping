<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
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

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }
    }
}

