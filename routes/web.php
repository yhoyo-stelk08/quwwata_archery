<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

// Home page - use HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/api/categories/navigation', [CategoryController::class, 'navigation'])->name('categories.navigation');
Route::get('/api/categories/search', [CategoryController::class, 'search'])->name('categories.search');

// Product routes
Route::resource('products', ProductController::class);
Route::get('/api/products/search', [ProductController::class, 'search'])->name('products.search');

// Other pages
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('/search', function () {
    return Inertia::render('Search', [
        'query' => request('q', '')
    ]);
})->name('search');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';