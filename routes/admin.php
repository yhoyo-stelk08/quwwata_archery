<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Categories CRUD
    Route::resource('categories', CategoryController::class);
    
    // Products CRUD
    Route::resource('products', ProductController::class);
    
    // Product Images CRUD
    Route::resource('product-images', ProductImageController::class);
});