<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\carousel_slide as CarouselSlide;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CarouselSlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides = CarouselSlide::orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($slide) {
                return [
                    'id' => $slide->id,
                    'title' => $slide->title,
                    'description' => $slide->description,
                    'badge' => $slide->badge,
                    'image_url' => $slide->image_url,
                    'primary_button_text' => $slide->primary_button_text,
                    'primary_button_url' => $slide->primary_button_url,
                    'secondary_button_text' => $slide->secondary_button_text,
                    'secondary_button_url' => $slide->secondary_button_url,
                    'sort_order' => $slide->sort_order,
                    'is_active' => $slide->is_active,
                    'created_at' => $slide->created_at->format('M d, Y'),
                ];
            });

        return Inertia::render('admin/CarouselSlides/Index', [
            'slides' => $slides,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/CarouselSlides/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'badge' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'primary_button_text' => 'required|string|max:50',
            'primary_button_url' => 'required|string|max:255',
            'secondary_button_text' => 'nullable|string|max:50',
            'secondary_button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('carousel-slides', 'public');
        }

        // Set default sort order
        if (!isset($validated['sort_order'])) {
            $validated['sort_order'] = CarouselSlide::max('sort_order') + 1;
        }

        CarouselSlide::create($validated);

        return redirect()->route('admin.carousel-slides.index')
            ->with('success', 'Carousel slide created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CarouselSlide $carousel_slide)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarouselSlide $carouselSlide)
    {
        return Inertia::render('admin/CarouselSlides/Edit', [
            'slide' => [
                'id' => $carouselSlide->id,
                'title' => $carouselSlide->title,
                'description' => $carouselSlide->description,
                'badge' => $carouselSlide->badge,
                'image_url' => $carouselSlide->image_url,
                'primary_button_text' => $carouselSlide->primary_button_text,
                'primary_button_url' => $carouselSlide->primary_button_url,
                'secondary_button_text' => $carouselSlide->secondary_button_text,
                'secondary_button_url' => $carouselSlide->secondary_button_url,
                'sort_order' => $carouselSlide->sort_order,
                'is_active' => $carouselSlide->is_active,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarouselSlide $carouselSlide)
    {
        // Debug: Log what we're receiving
        Log::info('Update request data:', [
            'all_data' => $request->all(),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'has_file' => $request->hasFile('image'),
            'method' => $request->method(),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'badge' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'primary_button_text' => 'required|string|max:50',
            'primary_button_url' => 'required|string|max:255',
            'secondary_button_text' => 'nullable|string|max:50',
            'secondary_button_url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'remove_image' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($carouselSlide->image && Storage::disk('public')->exists($carouselSlide->image)) {
                Storage::disk('public')->delete($carouselSlide->image);
            }
            $validated['image'] = $request->file('image')->store('carousel-slides', 'public');
        }

        // Handle image removal
        if ($request->boolean('remove_image') && $carouselSlide->image) {
            if (Storage::disk('public')->exists($carouselSlide->image)) {
                Storage::disk('public')->delete($carouselSlide->image);
            }
            $validated['image'] = null;
        }

        $carouselSlide->update($validated);

        return redirect()->route('admin.carousel-slides.index')
            ->with('success', 'Carousel slide updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarouselSlide $carouselSlide)
    {
        // Delete image file
        if ($carouselSlide->image && Storage::disk('public')->exists($carouselSlide->image)) {
            Storage::disk('public')->delete($carouselSlide->image);
        }

        $carouselSlide->delete();

        return redirect()->route('admin.carousel-slides.index')
            ->with('success', 'Carousel slide deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|exists:carousel_slides,id',
            'slides.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['slides'] as $slideData) {
            CarouselSlide::where('id', $slideData['id'])
                ->update(['sort_order' => $slideData['sort_order']]);
        }

        return response()->json(['message' => 'Slides reordered successfully']);
    }
}