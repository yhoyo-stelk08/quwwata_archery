<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Implement your authorization logic here
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id ?? $this->input('id');
        
        return [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($productId)
            ],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'current_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0|gt:current_price',
            'stock' => 'required|integer|min:0',
            'in_stock' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'sale_percentage' => 'nullable|integer|min:0|max:100',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
            'image_order' => 'nullable|array',
            'image_order.*' => 'integer|exists:product_images,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'slug.unique' => 'This slug is already taken by another product.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'current_price.required' => 'Current price is required.',
            'current_price.numeric' => 'Current price must be a valid number.',
            'original_price.gt' => 'Original price must be greater than current price.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'sale_percentage.max' => 'Sale percentage cannot exceed 100%.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.max' => 'Each image must not exceed 2MB.',
            'delete_images.*.exists' => 'Selected image does not exist.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set default values for boolean fields if not provided
        $this->merge([
            'in_stock' => $this->boolean('in_stock'),
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);

        // Generate slug from name if not provided
        if (!$this->has('slug') && $this->has('name')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->input('name'))
            ]);
        }
    }
}