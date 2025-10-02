<template>
    <AdminLayout>
        <Head :title="`Category: ${category.name}`" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <Link :href="route('admin.categories.index')" class="hover:text-amber-600">
                        Categories
                    </Link>
                    <ChevronRight class="h-4 w-4" />
                    <span>{{ category.name }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ category.name }}</h1>
                        <p class="text-gray-600 mt-2">View category details and products</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link
                            :href="route('admin.categories.edit', category.id)"
                            class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors"
                        >
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Category
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Category Details -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Category Details</h2>
                            
                            <!-- Image -->
                            <div v-if="category.image" class="mb-6">
                                <img
                                    :src="`/storage/${category.image}`"
                                    :alt="category.name"
                                    class="w-full h-48 object-cover rounded-lg"
                                />
                            </div>
                            <div v-else class="mb-6 w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <FolderOpen class="h-16 w-16 text-gray-300" />
                            </div>

                            <!-- Details -->
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded">
                                        {{ category.slug }}
                                    </dd>
                                </div>

                                <div v-if="category.description">
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ category.description }}
                                    </dd>
                                </div>

                                <div v-if="category.parent">
                                    <dt class="text-sm font-medium text-gray-500">Parent Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ category.parent.name }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sort Order</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ category.sort_order || 0 }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            :class="[
                                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                category.is_active
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800'
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    'w-1.5 h-1.5 mr-1.5 rounded-full',
                                                    category.is_active ? 'bg-green-400' : 'bg-red-400'
                                                ]"
                                            ></span>
                                            {{ category.is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Products</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ category.products?.length || 0 }} products
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ formatDate(category.created_at) }}
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ formatDate(category.updated_at) }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Products List -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Products in this Category</h2>
                        </div>

                        <!-- Products Table -->
                        <div v-if="category.products && category.products.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Product
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr
                                        v-for="product in category.products"
                                        :key="product.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img
                                                        v-if="product.images && product.images[0]"
                                                        :src="`/storage/${product.images[0].image_path}`"
                                                        :alt="product.name"
                                                        class="h-10 w-10 rounded object-cover"
                                                    />
                                                    <div
                                                        v-else
                                                        class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center"
                                                    >
                                                        <ShoppingCart class="h-5 w-5 text-gray-400" />
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ product.name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${{ Number(product.current_price || 0).toFixed(2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ product.stock || 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="[
                                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                                    product.is_active
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                ]"
                                            >
                                                {{ product.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link
                                                :href="route('admin.products.edit', product.id)"
                                                class="text-amber-600 hover:text-amber-900"
                                            >
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-12">
                            <ShoppingCart class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
                            <p class="mt-1 text-sm text-gray-500">This category doesn't have any products yet.</p>
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
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import {
    ChevronRight,
    Edit,
    FolderOpen,
    ShoppingCart,
    Plus,
} from 'lucide-vue-next';

interface ProductImage {
    id: number;
    image_path: string;
}

interface Product {
    id: number;
    name: string;
    current_price: number;
    stock: number;
    is_active: boolean;
    images?: ProductImage[];
}

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    image: string | null;
    sort_order: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    parent?: {
        name: string;
    };
    products?: Product[];
}

defineProps<{
    category: Category;
}>();

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
