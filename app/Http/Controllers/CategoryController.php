<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category as Category;
use App\Models\product as Product;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     */
    public function index()
    {
        $categories = Category::with(['children', 'activeProducts'])
            ->active()
            ->root()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image_url,
                    'products_count' => $category->products_count,
                    'children' => $category->activeChildren->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'products_count' => $child->products_count,
                        ];
                    }),
                ];
            });

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified category with its products
     */
    public function show(Category $category, Request $request)
    {
        // Load category with relationships
        $category->load(['children', 'parent']);

        // Get products for this category and its children
        $categoryIds = [$category->id];
        if ($category->hasChildren()) {
            $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
        }

        $query = Product::with(['primaryImage', 'category'])
            ->whereIn('category_id', $categoryIds)
            ->active();

        // Apply filters
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('current_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('current_price', '<=', $request->max_price);
        }

        // Apply sorting
        $sortBy = $request->get('sort', 'created_at');
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
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Transform products for frontend
        $products->getCollection()->transform(function ($product) {
            return [
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
                'image' => $product->main_image_url,
                'category' => [
                    'name' => $product->category->name,
                    'slug' => $product->category->slug,
                ],
            ];
        });

        // Get price range for filters
        $priceRange = Product::whereIn('category_id', $categoryIds)
            ->active()
            ->selectRaw('MIN(current_price) as min_price, MAX(current_price) as max_price')
            ->first();

        return Inertia::render('Categories/Show', [
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'description' => $category->description,
                'image' => $category->image_url,
                'breadcrumb' => $category->breadcrumb->map(function ($cat) {
                    return [
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                    ];
                }),
                'children' => $category->activeChildren->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->name,
                        'slug' => $child->slug,
                        'products_count' => $child->products_count,
                    ];
                }),
                'products_count' => $category->products_count,
            ],
            'products' => $products,
            'filters' => [
                'search' => $request->search,
                'min_price' => $request->min_price,
                'max_price' => $request->max_price,
                'sort' => $request->sort,
                'price_range' => [
                    'min' => $priceRange->min_price ?? 0,
                    'max' => $priceRange->max_price ?? 1000,
                ],
            ],
        ]);
    }

    /**
     * Get category navigation for header/menu
     */
    public function navigation()
    {
        $categories = Category::with(['activeChildren'])
            ->active()
            ->root()
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'children' => $category->activeChildren->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'slug' => $child->slug,
                        ];
                    }),
                ];
            });

        return response()->json($categories);
    }

    /**
     * Search categories (for autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $categories = Category::active()
            ->where('name', 'like', '%' . $query . '%')
            ->take(5)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'products_count' => $category->products_count,
                ];
            });

        return response()->json($categories);
    }
}