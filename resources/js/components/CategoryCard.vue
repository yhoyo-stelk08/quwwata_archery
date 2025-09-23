<template>
    <Link
        :href="category.href"
        class="group relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 bg-white"
    >
        <!-- Image -->
        <div class="aspect-[4/3] overflow-hidden">
            <img
                :src="category.image || '/images/placeholder-category.jpg'"
                :alt="category.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
        </div>
        
        <!-- Content Overlay -->
        <div class="absolute inset-0 flex flex-col justify-end p-6">
            <div class="text-white">
                <h3 class="text-xl md:text-2xl font-bold mb-2">
                    {{ category.name }}
                </h3>
                <p class="text-gray-200 mb-3">
                    {{ category.description }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-300">
                        {{ category.productCount }} Products
                    </span>
                    <div class="flex items-center text-amber-400 group-hover:text-amber-300 transition-colors">
                        <span class="text-sm font-semibold mr-1">Explore</span>
                        <ArrowRightIcon class="h-4 w-4 group-hover:translate-x-1 transition-transform" />
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hover Overlay -->
        <div class="absolute inset-0 bg-amber-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </Link>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRightIcon } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    description: string;
    image?: string;
    href: string;
    productCount: number;
}

interface Props {
    category: Category;
}

defineProps<Props>();
</script>