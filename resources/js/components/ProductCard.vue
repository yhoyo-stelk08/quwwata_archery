<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
        <!-- Product Image -->
        <div class="relative aspect-square bg-gray-100 overflow-hidden">
            <img
                :src="product.image || '/images/placeholder-product.jpg'"
                :alt="product.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            
            <!-- Sale Badge -->
            <div
                v-if="product.sale_percentage"
                class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded-full text-sm font-semibold"
            >
                %{{ product.sale_percentage }}
            </div>
            
            <!-- Quick Actions -->
            <div class="absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <button
                    @click="addToWishlist"
                    class="bg-white p-2 rounded-full shadow-md hover:bg-gray-50 transition-colors"
                    :class="{ 'text-red-500': isInWishlist, 'text-gray-600': !isInWishlist }"
                >
                    <HeartIcon class="h-5 w-5" :class="{ 'fill-current': isInWishlist }" />
                </button>
                <button
                    @click="quickView"
                    class="bg-white p-2 rounded-full shadow-md hover:bg-gray-50 transition-colors text-gray-600"
                >
                    <EyeIcon class="h-5 w-5" />
                </button>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="p-4">
            <!-- Product Name -->
            <Link
                :href="`/products/${product.slug}`"
                class="block hover:text-amber-600 transition-colors"
            >
                <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2">
                    {{ product.name }}
                </h3>
            </Link>
            
            <!-- Variations (if any) -->
            <div v-if="product.variations && product.variations.length > 0" class="mb-3">
                <div class="text-sm text-gray-600 mb-1">
                    {{ product.variations.length }} {{ product.variations[0].type }}
                </div>
                <div class="flex flex-wrap gap-1">
                    <span
                        v-for="variation in product.variations.slice(0, 3)"
                        :key="variation.id"
                        class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"
                    >
                        {{ variation.name }}
                    </span>
                    <span
                        v-if="product.variations.length > 3"
                        class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded"
                    >
                        +{{ product.variations.length - 3 }} more
                    </span>
                </div>
            </div>
            
            <!-- Price -->
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-bold text-gray-900">
                        €{{ product.current_price }}
                    </span>
                    <span
                        v-if="product.original_price && product.original_price !== product.current_price"
                        class="text-sm text-gray-500 line-through"
                    >
                        €{{ product.original_price }}
                    </span>
                </div>
                
                <!-- Rating -->
                <div v-if="product.rating" class="flex items-center space-x-1">
                    <div class="flex items-center">
                        <StarIcon
                            v-for="star in 5"
                            :key="star"
                            class="h-4 w-4"
                            :class="star <= product.rating ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                        />
                    </div>
                    <span class="text-sm text-gray-600">({{ product.reviews_count }})</span>
                </div>
            </div>
            
            <!-- Add to Cart Button -->
            <button
                @click="addToCart"
                :disabled="!product.in_stock"
                class="w-full bg-amber-600 hover:bg-amber-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200 flex items-center justify-center space-x-2"
            >
                <ShoppingCartIcon class="h-5 w-5" />
                <span v-if="product.in_stock">Add to Cart</span>
                <span v-else>Out of Stock</span>
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import {
    HeartIcon,
    EyeIcon,
    StarIcon,
    ShoppingCartIcon,
} from 'lucide-vue-next';

interface ProductVariation {
    id: number;
    type: string;
    name: string;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    image?: string;
    current_price: number;
    original_price?: number;
    sale_percentage?: number;
    rating?: number;
    reviews_count?: number;
    in_stock: boolean;
    variations?: ProductVariation[];
}

interface Props {
    product: Product;
}

const props = defineProps<Props>();

// State
const isInWishlist = ref(false); // This would come from your wishlist store/composable

// Methods
const addToCart = () => {
    if (props.product.in_stock) {
        // Handle add to cart logic
        console.log('Adding to cart:', props.product.id);
        // You would dispatch to a cart store or make an API call here
    }
};

const addToWishlist = () => {
    isInWishlist.value = !isInWishlist.value;
    // Handle wishlist logic
    console.log('Toggle wishlist:', props.product.id);
};

const quickView = () => {
    // Handle quick view modal
    console.log('Quick view:', props.product.id);
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>