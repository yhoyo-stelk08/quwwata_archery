<template>
    <AdminLayout>
        <Head title="Admin Dashboard" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-2">Welcome to your archery store admin panel</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <StatsCard
                    title="Total Products"
                    :value="stats.products"
                    icon="Package"
                    color="blue"
                    :href="route('admin.products.index')"
                />
                <StatsCard
                    title="Categories"
                    :value="stats.categories"
                    icon="FolderOpen"
                    color="green"
                    :href="route('admin.categories.index')"
                />
                <StatsCard
                    title="Product Images"
                    :value="stats.images"
                    icon="Image"
                    color="purple"
                    :href="route('admin.product-images.index')"
                />
                <StatsCard
                    title="Active Products"
                    :value="stats.activeProducts"
                    icon="CheckCircle"
                    color="emerald"
                />
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link
                        :href="route('admin.products.create')"
                        class="flex items-center p-4 bg-amber-50 hover:bg-amber-100 rounded-lg border border-amber-200 transition-colors group"
                    >
                        <div class="flex-shrink-0">
                            <Plus class="h-8 w-8 text-amber-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-amber-900">Add Product</p>
                            <p class="text-sm text-amber-700">Create a new archery product</p>
                        </div>
                    </Link>

                    <Link
                        :href="route('admin.categories.create')"
                        class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition-colors group"
                    >
                        <div class="flex-shrink-0">
                            <FolderPlus class="h-8 w-8 text-blue-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-900">Add Category</p>
                            <p class="text-sm text-blue-700">Create a product category</p>
                        </div>
                    </Link>

                    <Link
                        :href="route('admin.product-images.create')"
                        class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-colors group"
                    >
                        <div class="flex-shrink-0">
                            <ImagePlus class="h-8 w-8 text-green-600" />
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-900">Upload Image</p>
                            <p class="text-sm text-green-700">Add product images</p>
                        </div>
                    </Link>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Products -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Products</h2>
                        <Link
                            :href="route('admin.products.index')"
                            class="text-sm text-amber-600 hover:text-amber-700"
                        >
                            View all
                        </Link>
                    </div>
                    <div class="space-y-3">
                        <div
                            v-for="product in recentProducts"
                            :key="product.id"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <Package class="h-5 w-5 text-gray-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ product.name }}</p>
                                    <p class="text-xs text-gray-500">{{ product.category?.name }}</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                ${{ product.current_price }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Categories -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Categories</h2>
                        <Link
                            :href="route('admin.categories.index')"
                            class="text-sm text-amber-600 hover:text-amber-700"
                        >
                            View all
                        </Link>
                    </div>
                    <div class="space-y-3">
                        <div
                            v-for="category in categories"
                            :key="category.id"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <FolderOpen class="h-5 w-5 text-gray-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ category.name }}</p>
                                    <p class="text-xs text-gray-500">{{ category.products_count }} products</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span
                                    :class="[
                                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                        category.is_active 
                                            ? 'bg-green-100 text-green-800' 
                                            : 'bg-gray-100 text-gray-800'
                                    ]"
                                >
                                    {{ category.is_active ? 'Active' : 'Inactive' }}
                                </span>
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
import StatsCard from '@/components/admin/StatsCard.vue';
import {
    Package,
    FolderOpen,
    Image,
    CheckCircle,
    Plus,
    FolderPlus,
    ImagePlus
} from 'lucide-vue-next';

interface Product {
    id: number;
    name: string;
    current_price: string;
    category?: {
        name: string;
    };
}

interface Category {
    id: number;
    name: string;
    is_active: boolean;
    products_count: number;
}

interface Stats {
    products: number;
    categories: number;
    images: number;
    activeProducts: number;
}

defineProps<{
    stats: Stats;
    recentProducts: Product[];
    categories: Category[];
}>();
</script>