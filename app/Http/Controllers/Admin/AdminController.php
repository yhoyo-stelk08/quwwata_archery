<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product as Product;
use App\Models\category as Category;
use App\Models\product_image as ProductImage;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'images' => ProductImage::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
        ];

        $recentProducts = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $categories = Category::withCount('products')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('admin/Dashboard', [
            'stats' => $stats,
            'recentProducts' => $recentProducts,
            'categories' => $categories,
        ]);
    }
}