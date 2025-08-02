<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NewsApiController;



Route::get('/news', [NewsApiController::class, 'index']);

