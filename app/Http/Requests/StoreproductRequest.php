<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Implement your authorization logic here
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // ← Missing - Required!
            'description' => 'nullable|string',
            'current_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0|gt:current_price',
            'stock' => 'required|integer|min:0',
            'in_stock' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5', // ← Optional validation
            'reviews_count' => 'nullable|integer|min:0', // ← Optional validation
            'is_active' => 'boolean', // ← Optional validation
            'is_featured' => 'boolean', // ← Optional validation
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'current_price.required' => 'Current price is required.',
            'current_price.numeric' => 'Current price must be a valid number.',
            'original_price.gt' => 'Original price must be greater than current price.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.max' => 'Each image must not exceed 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set default values for boolean fields if not provided
        $this->merge([
            'in_stock' => $this->boolean('in_stock', true),
            'is_active' => $this->boolean('is_active', true),
            'is_featured' => $this->boolean('is_featured', false),
        ]);
    }
}