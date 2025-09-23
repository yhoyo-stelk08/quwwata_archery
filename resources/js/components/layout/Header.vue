<template>
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <!-- Top Banner -->
        <div class="bg-amber-700 text-white text-center py-2 text-sm">
            New season for 2025! Join our community of perfection!
        </div>
        
        <!-- Main Header -->
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <Link href="/" class="flex items-center space-x-2">
                        <div class="h-12 w-12 bg-amber-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">Q</span>
                        </div>
                        <span class="text-2xl font-bold text-amber-800">Quwwata Archery</span>
                    </Link>
                </div>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-8">
                    <div class="relative">
                        <input
                            type="text"
                            placeholder="Search for products..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            v-model="searchQuery"
                            @keyup.enter="handleSearch"
                        />
                        <button
                            @click="handleSearch"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-amber-600"
                        >
                            <SearchIcon class="h-5 w-5" />
                        </button>
                    </div>
                </div>
                
                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Account -->
                    <div class="relative" v-if="page.props.auth?.user">
                        <button
                            @click="toggleAccountMenu"
                            class="flex items-center space-x-1 text-gray-700 hover:text-amber-600"
                        >
                            <UserIcon class="h-6 w-6" />
                            <span class="hidden md:block">My Account</span>
                        </button>
                        
                        <!-- Account Dropdown -->
                        <div
                            v-show="showAccountMenu"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                        >
                            <Link href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Profile
                            </Link>
                            <Link href="/orders" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                My Orders
                            </Link>
                            <Link href="/logout" method="post" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </Link>
                        </div>
                    </div>
                    
                    <!-- Login/Register for guests -->
                    <div v-else class="flex items-center space-x-2">
                        <Link href="/login" class="text-gray-700 hover:text-amber-600">Login</Link>
                        <span class="text-gray-300">/</span>
                        <Link href="/register" class="text-gray-700 hover:text-amber-600">Sign Up</Link>
                    </div>
                    
                    <!-- Cart -->
                    <button
                        @click="toggleCart"
                        class="relative flex items-center space-x-1 text-gray-700 hover:text-amber-600"
                    >
                        <ShoppingCartIcon class="h-6 w-6" />
                        <span class="hidden md:block">Cart</span>
                        <span
                            v-if="cartCount > 0"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                        >
                            {{ cartCount }}
                        </span>
                    </button>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="border-t border-gray-200 py-4">
                <ul class="flex items-center space-x-8">
                    <li>
                        <Link href="/" class="text-gray-700 hover:text-amber-600 font-medium">
                            Home
                        </Link>
                    </li>
                    <li class="relative group">
                        <button class="text-gray-700 hover:text-amber-600 font-medium flex items-center">
                            Archery Equipment
                            <ChevronDownIcon class="h-4 w-4 ml-1" />
                        </button>
                        <!-- Dropdown -->
                        <div class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <Link href="/products/bows" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Traditional Bows
                            </Link>
                            <Link href="/products/arrows" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Arrows
                            </Link>
                            <Link href="/products/accessories" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Accessories
                            </Link>
                        </div>
                    </li>
                    <li>
                        <Link href="/products/historical-clothes" class="text-gray-700 hover:text-amber-600 font-medium">
                            Historical Clothes
                        </Link>
                    </li>
                    <li>
                        <Link href="/products/equestrian" class="text-gray-700 hover:text-amber-600 font-medium">
                            Equestrian Equipment
                        </Link>
                    </li>
                    <li>
                        <Link href="/about" class="text-gray-700 hover:text-amber-600 font-medium">
                            About Us
                        </Link>
                    </li>
                    <li>
                        <Link href="/contact" class="text-gray-700 hover:text-amber-600 font-medium">
                            Contact
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    SearchIcon,
    UserIcon,
    ShoppingCartIcon,
    ChevronDownIcon,
} from 'lucide-vue-next';

const page = usePage();

// State
const searchQuery = ref('');
const showAccountMenu = ref(false);
const cartCount = ref(0); // This would come from your cart store/composable

// Methods
const handleSearch = () => {
    if (searchQuery.value.trim()) {
        // Navigate to search results
        window.location.href = `/search?q=${encodeURIComponent(searchQuery.value)}`;
    }
};

const toggleAccountMenu = () => {
    showAccountMenu.value = !showAccountMenu.value;
};

const toggleCart = () => {
    // Open cart sidebar or navigate to cart page
    console.log('Toggle cart');
};
</script>