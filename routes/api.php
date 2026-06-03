<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


    Route::get('/categories/{id}', [CategoriesController::class, 'show'])->name('categories.show');
    // Route::put('/categories/{id}', [CategoriesController::class, 'show'])->name('categories.show');
    Route::get('/categories', [CategoriesController::class, 'Categories'])->name('categories');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    // Route::put('/categories/{id}', [CategoriesController::class, 'show'])->name('categories.show');
    Route::put('/categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');
