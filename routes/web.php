<?php


use App\Livewire\NewList;
use App\Livewire\NewsStatistics;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/news', NewList::class)->name('news');
    Route::get('/news/{id}', [NewList::class, 'show'])->name('news.show');
    Route::get('/news-statistics', NewsStatistics::class)->name('news-statistics');
});
