<?php

use App\Http\Controllers\ClothesController;
use App\Http\Controllers\OutfitController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:6,1');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');

    Route::get('/', [ClothesController::class, 'index'])->name('home');

    Route::get('/clothes', [ClothesController::class, 'index'])->name('clothes.index');
    Route::post('/clothes', [ClothesController::class, 'store'])->name('clothes.store');

    Route::get('/generate', [OutfitController::class, 'create'])->name('outfits.create');
    Route::get('/wardrobe', [OutfitController::class, 'index'])->name('outfits.index');
    Route::get('/wardrobe/status', [OutfitController::class, 'status'])->name('outfits.status');
    Route::post('/outfits', [OutfitController::class, 'store'])->name('outfits.store');
});
