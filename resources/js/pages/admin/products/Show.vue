<template>
    <AdminLayout>
        <Head :title="`Product: ${product.name}`" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <Link :href="route('admin.products.index')" class="hover:text-amber-600">
                        Products
                    </Link>
                    <ChevronRight class="h-4 w-4" />
                    <span>{{ product.name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ product.name }}</h1>
                        <p class="text-gray-600 mt-2">View product details</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link
                            :href="route('admin.products.edit', product.id)"
                            class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors"
                        >
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Product
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Product Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                        <!-- Product Images -->
                        <div v-if="product.images && product.images.length > 0" class="p-6 border-b">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Images</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                <div
                                    v-for="image in product.images"
                                    :key="image.id"
                                    class="relative"
                                >
                                    <img
                                        :src="image.image_url"
                                        :alt="image.alt_text"
                                        class="w-full h-32 object-cover rounded-lg border cursor-pointer hover:opacity-75 transition"
                                        @click="selectedImage = image.image_url"
                                    />
                                    <span
                                        v-if="image.is_primary"
                                        class="absolute top-2 left-2 bg-amber-500 text-white text-xs px-2 py-1 rounded"
                                    >
                                        Primary
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
                            
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">SKU / ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900">#{{ product.id }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ product.name }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">
                                        {{ product.slug }}
                                    </dd>
                                </div>

                                <div v-if="product.category">
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ product.category.name }}
                                        </span>
                                    </dd>
                                </div>

                                <div v-if="product.description">
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">
                                        {{ product.description }}
                                    </dd>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Current Price</dt>
                                        <dd class="mt-1 text-lg font-bold text-gray-900">
                                            ${{ Number(product.current_price || 0).toFixed(2) }}
                                        </dd>
                                    </div>

                                    <div v-if="product.original_price">
                                        <dt class="text-sm font-medium text-gray-500">Original Price</dt>
                                        <dd class="mt-1 text-lg text-gray-500 line-through">
                                            ${{ Number(product.original_price || 0).toFixed(2) }}
                                        </dd>
                                    </div>
                                </div>

                                <div v-if="product.sale_percentage" class="bg-red-50 px-4 py-3 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-red-700 font-semibold text-lg">
                                            {{ product.sale_percentage }}% OFF
                                        </span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-semibold">
                                            {{ product.stock }} units
                                        </dd>
                                    </div>

                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Stock Status</dt>
                                        <dd class="mt-1">
                                            <span
                                                :class="[
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                    product.in_stock
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                ]"
                                            >
                                                {{ product.in_stock ? 'In Stock' : 'Out of Stock' }}
                                            </span>
                                        </dd>
                                    </div>
                                </div>

                                <div v-if="product.rating">
                                    <dt class="text-sm font-medium text-gray-500">Rating</dt>
                                    <dd class="mt-1 flex items-center">
                                        <div class="flex items-center">
                                            <Star class="h-5 w-5 text-yellow-400 fill-current" />
                                            <span class="ml-1 text-sm font-semibold text-gray-900">
                                                {{ Number(product.rating || 0).toFixed(1) }}
                                            </span>
                                        </div>
                                        <span class="ml-2 text-sm text-gray-500">
                                            ({{ product.reviews_count || 0 }} reviews)
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ formatDate(product.created_at) }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ formatDate(product.updated_at) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden sticky top-4">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Status</h2>
                            
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Visibility</dt>
                                    <dd class="mt-1">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                product.is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 mr-1.5 rounded-full',
                                                    product.is_active ? 'bg-green-400' : 'bg-red-400'
                                                ]"
                                            ></span>
                                            {{ product.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Featured</dt>
                                    <dd class="mt-1">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                product.is_featured
                                                    ? 'bg-amber-100 text-amber-800'
                                                    : 'bg-gray-100 text-gray-800'
                                            ]"
                                        >
                                            {{ product.is_featured ? 'Yes' : 'No' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div
            v-if="selectedImage"
            class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-75 flex items-center justify-center p-4"
            @click="selectedImage = null"
        >
            <div class="relative max-w-4xl w-full">
                <img
                    :src="selectedImage"
                    alt="Full size image"
                    class="w-full h-auto rounded-lg"
                />
                <button
                    @click="selectedImage = null"
                    class="absolute top-4 right-4 bg-white rounded-full p-2 hover:bg-gray-100"
                >
                    <X class="h-6 w-6 text-gray-700" />
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import {
    ChevronRight,
    Edit,
    Star,
    X,
} from 'lucide-vue-next';

interface ProductImage {
    id: number;
    image_url: string;
    alt_text: string;
    is_primary: boolean;
}

interface Category {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    current_price: number;
    original_price: number | null;
    stock: number;
    in_stock: boolean;
    is_active: boolean;
    is_featured: boolean;
    sale_percentage: number | null;
    rating: number | null;
    reviews_count: number | null;
    created_at: string;
    updated_at: string;
    category?: Category;
    images?: ProductImage[];
}

defineProps<{
    product: Product;
}>();

const selectedImage = ref<string | null>(null);

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>
