<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Models\CurrencyRate;
use App\Http\Controllers\CurrencyRateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/create', [NewsController::class, 'create'])->name('news.create');
Route::post('/news', [NewsController::class, 'store'])->name('news.store');
Route::get('news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
Route::put('news/{id}', [NewsController::class, 'update'])->name('news.update');
Route::delete('news/{id}', [NewsController::class, 'destroy'])->name('news.destroy');

Route::get('/rates', function () {
    return CurrencyRate::all();
});


Route::get('/currency-fetch', [\App\Http\Controllers\CurrencyController::class, 'fetch']);

Route::get('/currency-rates', [CurrencyRateController::class, 'index']);

require __DIR__.'/auth.php';
