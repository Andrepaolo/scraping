<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryStatistics extends Component
{
    public $categoryPercentages;

    public function mount()
{
    // Obtén las estadísticas de las categorías y pásalas al componente
    $this->categoryPercentages = $this->getCategoryStatistics();
}

public function getCategoryStatistics()
{
    // Supón que obtienes las categorías desde la base de datos
    $categories = Category::all();

    // Si no hay categorías, evitar error
    if ($categories->isEmpty()) {
        return [
            'labels' => [],
            'values' => [],
            'colors' => []
        ];
    }

    return [
        'labels' => $categories->pluck('name')->toArray(),  // Etiquetas: nombre de las categorías
        'values' => $categories->map(function ($category) {
            return $category->news()->count();  // Aquí puedes cambiarlo para otros valores que necesites
        })->toArray(),
        'colors' => $categories->map(function ($category) {
            return $category->color ?? '#D1D5DB';  // Colores para cada categoría
        })->toArray(),
    ];
}


    public function render()
    {
        return view('livewire.category-statistics');
    }
}
