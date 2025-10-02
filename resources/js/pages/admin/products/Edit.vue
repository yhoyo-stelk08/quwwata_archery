<template>
    <AdminLayout>
        <Head title="Edit Product" />

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <Link :href="route('admin.products.index')" class="hover:text-amber-600">
                        Products
                    </Link>
                    <ChevronRight class="h-4 w-4" />
                    <span>Edit</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
                <p class="text-gray-600 mt-2">Update product details</p>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Product Name -->
                    <div>
                        <Label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name *
                        </Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            name="name"
                            required
                            placeholder="Enter product name"
                            class="w-full"
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <!-- Category -->
                    <div>
                        <Label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category *
                        </Label>
                        <select
                            id="category_id"
                            v-model="form.category_id"
                            name="category_id"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        >
                            <option value="">Select a category</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.category_id" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <Label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            name="description"
                            rows="4"
                            placeholder="Enter product description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <!-- Pricing -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <Label for="current_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Current Price * ($)
                            </Label>
                            <Input
                                id="current_price"
                                v-model="form.current_price"
                                type="number"
                                step="0.01"
                                min="0"
                                name="current_price"
                                required
                                placeholder="0.00"
                                class="w-full"
                            />
                            <InputError :message="form.errors.current_price" class="mt-2" />
                        </div>

                        <div>
                            <Label for="original_price" class="block text-sm font-medium text-gray-700 mb-2">
                                Original Price ($)
                            </Label>
                            <Input
                                id="original_price"
                                v-model="form.original_price"
                                type="number"
                                step="0.01"
                                min="0"
                                name="original_price"
                                placeholder="0.00"
                                class="w-full"
                            />
                            <p class="text-sm text-gray-500 mt-1">
                                Leave empty if not on sale
                            </p>
                            <InputError :message="form.errors.original_price" class="mt-2" />
                        </div>
                    </div>

                    <!-- Stock -->
                    <div>
                        <Label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            Stock Quantity *
                        </Label>
                        <Input
                            id="stock"
                            v-model="form.stock"
                            type="number"
                            min="0"
                            name="stock"
                            required
                            placeholder="0"
                            class="w-full max-w-xs"
                        />
                        <InputError :message="form.errors.stock" class="mt-2" />
                    </div>

                    <!-- Existing Images -->
                    <div v-if="product.images && product.images.length > 0">
                        <Label class="block text-sm font-medium text-gray-700 mb-2">
                            Current Images
                        </Label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            <div
                                v-for="image in product.images"
                                :key="image.id"
                                class="relative"
                            >
                                <img
                                    :src="image.image_url"
                                    :alt="image.alt_text"
                                    class="w-full h-24 object-cover rounded-lg border"
                                />
                                <button
                                    type="button"
                                    @click="toggleImageForDeletion(image.id)"
                                    :class="[
                                        'absolute -top-2 -right-2 rounded-full p-1 transition-colors',
                                        imagesToDelete.includes(image.id)
                                            ? 'bg-red-500 text-white'
                                            : 'bg-gray-200 text-gray-700 hover:bg-red-500 hover:text-white'
                                    ]"
                                >
                                    <X class="h-3 w-3" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- New Product Images -->
                    <div>
                        <Label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            Add New Images
                        </Label>
                        <input
                            id="images"
                            type="file"
                            multiple
                            accept="image/*"
                            @change="handleImagesChange"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        />
                        <p class="text-sm text-gray-500 mt-1">
                            Select multiple images. Accepted formats: JPEG, PNG, JPG, WebP. Max size per image: 2MB
                        </p>
                        <InputError :message="form.errors.images" class="mt-2" />
                        
                        <!-- New Image Previews -->
                        <div v-if="imagePreviews.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            <div
                                v-for="(preview, index) in imagePreviews"
                                :key="index"
                                class="relative"
                            >
                                <img
                                    :src="preview"
                                    :alt="`Preview ${index + 1}`"
                                    class="w-full h-24 object-cover rounded-lg border"
                                />
                                <button
                                    type="button"
                                    @click="removeNewImage(index)"
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                >
                                    <X class="h-3 w-3" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Status Options -->
                    <div class="space-y-4">
                        <div>
                            <Label class="flex items-center space-x-2">
                                <Checkbox
                                    v-model="form.in_stock"
                                    name="in_stock"
                                />
                                <span class="text-sm font-medium text-gray-700">In Stock</span>
                            </Label>
                            <p class="text-sm text-gray-500 mt-1">
                                Mark as available for purchase
                            </p>
                        </div>

                        <div>
                            <Label class="flex items-center space-x-2">
                                <Checkbox
                                    v-model="form.is_active"
                                    name="is_active"
                                />
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </Label>
                            <p class="text-sm text-gray-500 mt-1">
                                Product will be visible on the website
                            </p>
                        </div>

                        <div>
                            <Label class="flex items-center space-x-2">
                                <Checkbox
                                    v-model="form.is_featured"
                                    name="is_featured"
                                />
                                <span class="text-sm font-medium text-gray-700">Featured Product</span>
                            </Label>
                            <p class="text-sm text-gray-500 mt-1">
                                Display in featured products section
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <Link
                            :href="route('admin.products.index')"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                        >
                            Cancel
                        </Link>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors flex items-center"
                        >
                            <LoaderCircle
                                v-if="form.processing"
                                class="animate-spin -ml-1 mr-2 h-4 w-4"
                            />
                            {{ form.processing ? 'Updating...' : 'Update Product' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { LoaderCircle, ChevronRight, X } from 'lucide-vue-next';

interface ProductImage {
    id: number;
    image_url: string;
    alt_text: string;
}

interface Product {
    id: number;
    name: string;
    category_id: number;
    description: string | null;
    current_price: number;
    original_price: number | null;
    stock: number;
    in_stock: boolean;
    is_active: boolean;
    is_featured: boolean;
    images?: ProductImage[];
}

interface Category {
    id: number;
    name: string;
}

const props = defineProps<{
    product: Product;
    categories: Category[];
}>();

const form = useForm({
    name: props.product.name,
    category_id: props.product.category_id,
    description: props.product.description || '',
    current_price: props.product.current_price,
    original_price: props.product.original_price || '',
    stock: props.product.stock,
    images: [] as File[],
    delete_images: [] as number[],
    in_stock: props.product.in_stock,
    is_active: props.product.is_active,
    is_featured: props.product.is_featured,
    _method: 'PUT',
});

const imagePreviews = ref<string[]>([]);
const imagesToDelete = ref<number[]>([]);

const handleImagesChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = Array.from(target.files || []);
    
    form.images = files;
    imagePreviews.value = [];
    
    files.forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviews.value.push(e.target?.result as string);
        };
        reader.readAsDataURL(file);
    });
};

const removeNewImage = (index: number) => {
    form.images.splice(index, 1);
    imagePreviews.value.splice(index, 1);
};

const toggleImageForDeletion = (imageId: number) => {
    const index = imagesToDelete.value.indexOf(imageId);
    if (index > -1) {
        imagesToDelete.value.splice(index, 1);
    } else {
        imagesToDelete.value.push(imageId);
    }
    form.delete_images = imagesToDelete.value;
};

const submit = () => {
    form.post(route('admin.products.update', props.product.id), {
        forceFormData: true,
    });
};
</script>
