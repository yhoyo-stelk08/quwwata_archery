<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\product_image as ProductImage;
use App\Models\product as Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index()
    {
        $images = ProductImage::with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('admin/product-images/Index', [
            'images' => $images,
        ]);
    }

    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('admin/product-images/Create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        $productImage = ProductImage::create([
            'product_id' => $validated['product_id'],
            'image_path' => $imagePath,
            'alt_text' => $validated['alt_text'] ?? Product::find($validated['product_id'])->name,
        ]);

        return redirect()->route('admin.product-images.index')
            ->with('success', 'Product image uploaded successfully.');
    }

    public function show(ProductImage $productImage)
    {
        $productImage->load('product');

        return Inertia::render('admin/product-images/Show', [
            'productImage' => $productImage,
        ]);
    }

    public function edit(ProductImage $productImage)
    {
        $productImage->load('product');
        
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('admin/product-images/Edit', [
            'productImage' => $productImage,
            'products' => $products,
        ]);
    }

    public function update(Request $request, ProductImage $productImage)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'alt_text' => 'nullable|string|max:255',
        ]);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            Storage::disk('public')->delete($productImage->image_path);
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $imagePath;
        }

        $productImage->update($validated);

        return redirect()->route('admin.product-images.index')
            ->with('success', 'Product image updated successfully.');
    }

    public function destroy(ProductImage $productImage)
    {
        // Delete the image file
        Storage::disk('public')->delete($productImage->image_path);
        
        $productImage->delete();

        return redirect()->route('admin.product-images.index')
            ->with('success', 'Product image deleted successfully.');
    }
}