<?php

use App\Http\Controllers\ClothesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clothes', [ClothesController::class, 'index']);
Route::post('/clothes', [ClothesController::class, 'store']);
