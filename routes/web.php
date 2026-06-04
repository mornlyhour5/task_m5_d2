<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoriesController;


Route::get('/', function () {
    return view('Auth.Login');
});
Route::get('/register', function () {
    return view('Auth.Register');
});


Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
// Route::post('/register', [AuthController::class, 'register']);
Route::post('/register', [AuthController::class, 'store']);


Route::middleware(EnsureTokenIsValid::class)->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');




    Route::get('/products', [ProductController::class, 'Product']);
    // Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products', [ProductController::class, 'create'])->name('products.create');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/export', [ProductController::class, 'export']);
    Route::post('/products/import', [ProductController::class, 'import']);




    Route::get('/categories', [CategoriesController::class, 'Categories'])->name('categories');
    Route::post('/categories', [CategoriesController::class, 'store'])->name('categories.store');
    Route::put('/categories/{id}', [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->name('categories.destroy');


});
