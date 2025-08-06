<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Models\CurrencyRate;
use App\Http\Controllers\CurrencyRateController;
use App\Jobs\DefaultJob;
use App\Jobs\CommentImportJob;
use App\Http\Controllers\ExcelController;

require __DIR__.'/auth.php';

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

Route::get('/test-default-job', function () {
    DefaultJob::dispatch();
    return "Default job dispatch edildi!";
});

Route::get('/test-comment-import', function () {
    $url = 'https://jsonplaceholder.typicode.com/comments';
    CommentImportJob::dispatch($url)->onQueue('comments_queue');
    return 'CommentImportJob URL ilə queue-ya göndərildi!';
});

Route::prefix('excel')->name('excel.')->group(function () {
    
    // Excel səhifəsi
    Route::get('/', [ExcelController::class, 'index'])->name('index');
    
    // Products Export
    Route::get('/products/export', [ExcelController::class, 'exportProducts'])->name('products.export');
    
    // Products Import
    Route::post('/products/import', [ExcelController::class, 'importProducts'])->name('products.import');
    
    // Template Download
    Route::get('/products/template', [ExcelController::class, 'downloadTemplate'])->name('products.template');
});


