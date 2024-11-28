<?php

namespace App\Livewire;

use App\Models\News;
use App\Models\Category;
use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;
use LaravelDaily\LaravelCharts\Classes\LaravelChart; // Importa la clase del gráfico

class NewList extends Component
{
    use WithPagination;

    public $search;
    public $categoryFilter;
    public $locationFilter;
    public $categoryPercentages = [];

    protected $listeners = ['render'];

    // Función para obtener las noticias filtradas
    public function render()
    {
        // Obtener todas las categorías y ubicaciones
        $categories = Category::all();
        $locations = Location::all();

        // Filtrar noticias según los filtros seleccionados
        $newsQuery = News::query();

        // Aplicamos los filtros si están establecidos
        if ($this->categoryFilter) {
            $newsQuery->where('category_id', $this->categoryFilter);
        }

        if ($this->locationFilter) {
            $newsQuery->where('location_id', $this->locationFilter);
        }

        if ($this->search) {
            $newsQuery->where('title', 'like', '%' . $this->search . '%');
        }

        // Obtener las noticias con paginación
        $news = $newsQuery->orderBy('id', 'desc')->paginate(16);

        // Obtener el porcentaje de noticias por categoría
        $this->categoryPercentages = $this->getCategoryPercentages();



        // Pasar los datos a la vista
        return view('livewire.new-list', [
            'news' => $news,
            'categories' => $categories,
            'locations' => $locations,
            'categoryPercentages' => $this->categoryPercentages, // Pasamos los datos al frontend
        ])->layout('layouts.app');
    }


    // Función para calcular el porcentaje de noticias por categoría
    private function getCategoryPercentages()
    {
        $categories = Category::all();
        $totalNews = News::count();

        $percentages = [
            'labels' => [],
            'values' => [],
            'colors' => [],
        ];

        if ($totalNews > 0) {
            $categoryCounts = News::selectRaw('category_id, COUNT(*) as count')
                ->groupBy('category_id')
                ->pluck('count', 'category_id');

            foreach ($categories as $category) {
                $categoryCount = $categoryCounts[$category->id] ?? 0;
                $percentages['labels'][] = $category->name;
                $percentages['values'][] = ($categoryCount / $totalNews) * 100;
                $percentages['colors'][] = $category->color ?? '#D1D5DB';
            }
        }

        return $percentages;
    }

    // Método para aplicar los filtros cuando el usuario hace clic en el botón
    public function applyFilters()
    {
        $this->resetPage();  // Resetea la paginación al primer número de página
    }
}
