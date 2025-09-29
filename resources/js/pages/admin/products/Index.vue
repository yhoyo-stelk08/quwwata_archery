<template>
    <AdminLayout>
        <Head title="Products" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Products</h1>
                    <p class="text-gray-600 mt-2">Manage your archery products</p>
                </div>
                <Link
                    :href="route('admin.products.create')"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors shadow-lg hover:shadow-xl"
                >
                    <Plus class="h-5 w-5 mr-2" />
                    Add Product
                </Link>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <div
                    v-for="product in products.data"
                    :key="product.id"
                    class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow"
                >
                    <!-- Product Image -->
                    <div class="aspect-square relative bg-gray-100">
                        <img
                            v-if="product.images && product.images.length > 0"
                            :src="`/storage/${product.images[0].image_path}`"
                            :alt="product.name"
                            class="w-full h-full object-cover"
                        />
                        <div
                            v-else
                            class="w-full h-full flex items-center justify-center"
                        >
                            <Package class="h-16 w-16 text-gray-300" />
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="absolute top-2 right-2">
                            <span
                                :class="[
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                    product.is_active
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800'
                                ]"
                            >
                                {{ product.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <!-- Featured Badge -->
                        <div v-if="product.is_featured" class="absolute top-2 left-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                <Star class="h-3 w-3 mr-1" />
                                Featured
                            </span>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <div class="mb-2">
                            <h3 class="font-semibold text-gray-900 text-sm line-clamp-2">
                                {{ product.name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ product.category?.name }}
                            </p>
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-lg font-bold text-gray-900">
                                    ${{ parseFloat(product.current_price).toFixed(2) }}
                                </span>
                                <span
                                    v-if="product.original_price && product.original_price > product.current_price"
                                    class="text-sm text-gray-500 line-through"
                                >
                                    ${{ parseFloat(product.original_price).toFixed(2) }}
                                </span>
                                <span
                                    v-if="product.sale_percentage && product.sale_percentage > 0"
                                    class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full font-medium"
                                >
                                    -{{ Math.round(product.sale_percentage) }}%
                                </span>
                            </div>
                        </div>

                        <!-- Stock Info -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Stock:</span>
                                <span
                                    :class="[
                                        'font-medium',
                                        product.stock > 0 ? 'text-green-600' : 'text-red-600'
                                    ]"
                                >
                                    {{ product.stock }} units
                                </span>
                            </div>
                        </div>

                        <!-- Images Count -->
                        <div class="mb-4">
                            <span class="inline-flex items-center text-xs text-gray-500">
                                <Image class="h-3 w-3 mr-1" />
                                {{ product.images_count }} image{{ product.images_count === 1 ? '' : 's' }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-3 border-t">
                            <Link
                                :href="route('admin.products.show', product.id)"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                            >
                                <Eye class="h-4 w-4 inline mr-1" />
                                View
                            </Link>
                            <div class="flex items-center space-x-2">
                                <Link
                                    :href="route('admin.products.edit', product.id)"
                                    class="text-amber-600 hover:text-amber-800 p-1"
                                    title="Edit"
                                >
                                    <Edit class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="deleteProduct(product)"
                                    class="text-red-600 hover:text-red-800 p-1"
                                    title="Delete"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="products.data.length === 0" class="text-center py-12">
                <Package class="mx-auto h-16 w-16 text-gray-400" />
                <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                <p class="mt-2 text-sm text-gray-500">Get started by creating your first product.</p>
                <div class="mt-6">
                    <Link
                        :href="route('admin.products.create')"
                        class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors"
                    >
                        <Plus class="h-5 w-5 mr-2" />
                        Add Product
                    </Link>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="products.links && products.links.length > 3" class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-1">
                    <Link
                        v-for="link in products.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        v-html="link.label"
                        :class="[
                            'px-3 py-2 text-sm font-medium rounded-md transition-colors',
                            link.active
                                ? 'bg-amber-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                        ]"
                    />
                </nav>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import {
    Plus,
    Package,
    Image,
    Star,
    Eye,
    Edit,
    Trash2,
} from 'lucide-vue-next';

interface ProductImage {
    image_path: string;
}

interface Product {
    id: number;
    name: string;
    current_price: string;
    original_price?: string;
    sale_percentage?: number;
    stock: number;
    is_active: boolean;
    is_featured: boolean;
    images_count: number;
    images?: ProductImage[];
    category?: {
        name: string;
    };
}

interface PaginatedProducts {
    data: Product[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

defineProps<{
    products: PaginatedProducts;
}>();

const deleteProduct = (product: Product) => {
    if (confirm(`Are you sure you want to delete "${product.name}"? This action cannot be undone.`)) {
        router.delete(route('admin.products.destroy', product.id));
    }
};
</script>