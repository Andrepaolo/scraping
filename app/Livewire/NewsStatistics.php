<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;
use App\Models\Category;
use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsStatistics extends Component
{
    public $locationStats = [];
    public $categoryStats = [];
    public $newsByDate = [];
    public $newsByDateAndCategory = [];
    public $newsByMonth = [];
    public $categoryDistribution = [];
    public $newsByCityCategoryDate = [];

    public function mount()
    {
        // Estadísticas por ubicación
        $this->locationStats = News::query()
            ->selectRaw('locations.name as location, COUNT(news.id) as total')
            ->join('locations', 'locations.id', '=', 'news.location_id')
            ->groupBy('locations.name')
            ->get(); // Devolviendo una colección de Eloquent (no convertir a array)

        // Estadísticas por categoría
        $this->categoryStats = News::query()
            ->selectRaw('categories.name as category, COUNT(news.id) as total')
            ->join('categories', 'categories.id', '=', 'news.category_id')
            ->groupBy('categories.name')
            ->get(); // Devolviendo una colección de Eloquent (no convertir a array)

        // Estadísticas por fecha y categoría
        $this->newsByDateAndCategory = News::query()
        ->selectRaw('DATE(news.date) as date, categories.name as category, COUNT(news.id) as total')
        ->join('categories', 'categories.id', '=', 'news.category_id')
        ->groupByRaw('DATE(news.date), categories.name')
        ->orderBy('date', 'asc')
        ->get()
        ->toArray();

        // Estadísticas por fecha
        $this->newsByDate = News::query()
        ->selectRaw('DATE(news.date) as date, COUNT(news.id) as total')
        ->groupByRaw('DATE(news.date)')
        ->orderBy('date', 'asc')
        ->get()
        ->toArray();

        // Estadísticas por mes
        $this->newsByMonth = News::query()
        ->selectRaw('YEAR(news.date) as year, MONTH(news.date) as month, COUNT(news.id) as total')
        ->groupByRaw('YEAR(news.date), MONTH(news.date)')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get()
        ->toArray();

                // Estadísticas por Categoría (para el gráfico de torta)
        $this->categoryDistribution = News::query()
        ->selectRaw('categories.name as category, COUNT(news.id) as total')
        ->join('categories', 'categories.id', '=', 'news.category_id')
        ->groupBy('categories.name')
        ->get()
        ->toArray();

        // Estadísticas por Ciudad, Categoría y Fecha
        $this->newsByCityCategoryDate = News::query()
        ->selectRaw('locations.name as city, categories.name as category, DATE(news.date) as date, COUNT(news.id) as total')
        ->join('locations', 'locations.id', '=', 'news.location_id')
        ->join('categories', 'categories.id', '=', 'news.category_id')
        ->groupByRaw('locations.name, categories.name, DATE(news.date)')
        ->orderBy('date', 'asc')
        ->get()
        ->toArray();

        // Estadísticas por Ciudad, Categoría y Fecha
        $this->newsByCityCategoryDate = News::query()
        ->selectRaw('locations.name as city, categories.name as category, DATE(news.created_at) as date, COUNT(news.id) as total')
        ->join('locations', 'locations.id', '=', 'news.location_id')
        ->join('categories', 'categories.id', '=', 'news.category_id')
        ->groupByRaw('locations.name, categories.name, DATE(news.created_at)')
        ->orderBy('date', 'asc')
        ->get()
        ->toArray();





    }

    public function render()
    {
        return view('livewire.news-statistics')->layout('layouts.app');
    }
}
