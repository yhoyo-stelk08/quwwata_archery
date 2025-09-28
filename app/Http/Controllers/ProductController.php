<?php

namespace App\Http\Controllers;

use App\Models\product as Product;
use App\Models\product_image as ProductImage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'images']);

        // Search functionality
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            // You'll need to add category relationship later
            // $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('current_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('current_price', '<=', $request->max_price);
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('current_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('current_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'popular':
                $query->orderBy('reviews_count', 'desc');
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12)->withQueryString();

        // Transform products for frontend
        $products->getCollection()->transform(function ($product) {
            return $this->transformProduct($product);
        });

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'sort' => $request->sort,
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['images', 'primaryImage']);

        // Get related products (same category or similar price range)
        $relatedProducts = Product::with(['primaryImage'])
            ->where('id', '!=', $product->id)
            ->whereBetween('current_price', [
                $product->current_price * 0.7,
                $product->current_price * 1.3
            ])
            ->take(4)
            ->get()
            ->map(fn($p) => $this->transformProduct($p));

        return Inertia::render('Products/Show', [
            'product' => $this->transformProduct($product, true), // detailed view
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        
        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Create product
        $product = Product::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['images']);
        
        return Inertia::render('Products/Edit', [
            'product' => $this->transformProduct($product, true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        
        // Update slug if name changed
        if ($validated['name'] !== $product->name) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure unique slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Product::where('slug', $validated['slug'])
                          ->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $product->update($validated);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        // Handle image deletions
        if ($request->filled('delete_images')) {
            $this->handleImageDeletions($request->delete_images);
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete all product images from storage
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Handle multiple image uploads
     */
    private function handleImageUploads(Product $product, array $images)
    {
        $sortOrder = $product->images()->max('sort_order') ?? 0;

        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'alt_text' => $product->name . ' - Image ' . ($index + 1),
                'sort_order' => $sortOrder + $index + 1,
                'is_primary' => $product->images()->count() === 0 && $index === 0, // First image is primary if no images exist
            ]);
        }
    }

    /**
     * Handle image deletions
     */
    private function handleImageDeletions(array $imageIds)
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();
        
        foreach ($images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }
    }

    /**
     * Transform product for frontend
     */
    private function transformProduct(Product $product, bool $detailed = false)
    {
        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'currentPrice' => $product->current_price,
            'originalPrice' => $product->original_price,
            'rating' => $product->rating,
            'reviewsCount' => $product->reviews_count,
            'salePercentage' => $product->sale_percentage,
            'inStock' => $product->in_stock,
            'stock' => $product->stock,
            'image' => $product->main_image_url,
        ];

        if ($detailed) {
            $data['images'] = $product->image_urls;
            $data['createdAt'] = $product->created_at;
            $data['updatedAt'] = $product->updated_at;
        }

        return $data;
    }

    /**
     * API endpoint for product search (for search autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::with('primaryImage')
            ->where('name', 'like', '%' . $query . '%')
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->current_price,
                    'image' => $product->main_image_url,
                ];
            });

        return response()->json($products);
    }

    /**
     * Set primary image for a product
     */
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        // Remove primary status from all images
        $product->images()->update(['is_primary' => false]);
        
        // Set new primary image
        $image->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Reorder product images
     */
    public function reorderImages(Product $product, Request $request)
    {
        $imageIds = $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'integer|exists:product_images,id',
        ])['image_ids'];

        foreach ($imageIds as $index => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}