<?php

use App\Livewire\NewList;
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
    Route::get('/news', NewList::class);
    Route::get('/news/{id}', [NewList::class, 'show'])->name('news.show');
});
