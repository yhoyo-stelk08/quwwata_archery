<template>
    <EcommerceLayout>
        <!-- Hero Carousel -->
        <HeroCarousel :slides="carouselSlides" />
        
        <!-- Best Sellers Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        The Best Sellers
                    </h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Discover our most popular products loved by archery enthusiasts worldwide
                    </p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <ProductCard
                        v-for="product in bestSellers"
                        :key="product.id"
                        :product="product"
                    />
                </div>
                
                <div class="text-center mt-8">
                    <Link
                        href="/products/best-sellers"
                        class="inline-flex items-center bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200"
                    >
                        View All Best Sellers
                        <ArrowRightIcon class="h-5 w-5 ml-2" />
                    </Link>
                </div>
            </div>
        </section>
        
        <!-- Categories Grid -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Shop by Category
                    </h2>
                    <p class="text-lg text-gray-600">
                        Explore our complete range of archery and historical equipment
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <CategoryCard
                        v-for="category in categories"
                        :key="category.id"
                        :category="category"
                    />
                </div>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mx-auto mb-4">
                            <ShieldCheckIcon class="h-8 w-8 text-green-600" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Safe Shopping with 256 Bit SSL
                        </h3>
                        <p class="text-gray-600">
                            Your personal and payment information is always secure with our advanced encryption.
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4">
                            <TruckIcon class="h-8 w-8 text-blue-600" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            The Fastest and Safest Delivery
                        </h3>
                        <p class="text-gray-600">
                            Get your orders delivered quickly and safely to your doorstep worldwide.
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="flex items-center justify-center w-16 h-16 bg-amber-100 rounded-full mx-auto mb-4">
                            <CreditCardIcon class="h-8 w-8 text-amber-600" />
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            Global Currency Options
                        </h3>
                        <p class="text-gray-600">
                            Shop in your preferred currency with multiple payment options available.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Company Story -->
        <section class="py-16 bg-gradient-to-r from-amber-50 to-amber-100">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                            Quwwata Archery LTD
                        </h2>
                        <p class="text-lg text-gray-700 mb-6">
                            Quwwata Archery LTD, which has been serving archery enthusiasts worldwide since 2017, 
                            produces products with historical foundations using the highest quality materials.
                        </p>
                        <p class="text-lg text-gray-700 mb-8">
                            With our Equestrian School which we opened in 2021, we provide professional level 
                            Horseback Archery clinics to guests from around the world.
                        </p>
                        <Link
                            href="/about"
                            class="inline-flex items-center bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200"
                        >
                            Learn More About Us
                            <ArrowRightIcon class="h-5 w-5 ml-2" />
                        </Link>
                    </div>
                    
                    <div class="relative">
                        <img
                            src="/images/company-story.jpg"
                            alt="Quwwata Archery Workshop"
                            class="w-full h-auto rounded-lg shadow-lg"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-lg"></div>
                    </div>
                </div>
            </div>
        </section>
    </EcommerceLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import EcommerceLayout from '../layouts/EcommerceLayout.vue';
import HeroCarousel from '../components/HeroCarousel.vue';
import ProductCard from '../components/ProductCard.vue';
import CategoryCard from '../components/CategoryCard.vue';
import { Link } from '@inertiajs/vue3';
import {
    ArrowRightIcon,
    ShieldCheckIcon,
    TruckIcon,
    CreditCardIcon,
} from 'lucide-vue-next';

interface CarouselSlide {
    id: number;
    title: string;
    description: string;
    badge: string | null;
    image_url: string;
    primary_button_text: string;
    primary_button_url: string;
    secondary_button_text: string | null;
    secondary_button_url: string | null;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    current_price: number;
    original_price?: number;
    sale_percentage?: number;
    rating: number;
    reviews_count: number;
    in_stock: boolean;
    image: string;
    variations?: Array<{
        id: number;
        type: string;
        name: string;
    }>;
}

interface Category {
    id: number;
    name: string;
    description: string;
    image: string;
    href: string;
    productCount: number;
}

const props = withDefaults(defineProps<{
    carouselSlides?: CarouselSlide[];
    bestSellers?: Product[];
    categories?: Category[];
}>(), {
    carouselSlides: () => [],
    bestSellers: () => [],
    categories: () => []
});
</script>