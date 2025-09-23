<template>
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <!-- Top Banner -->
        <div class="bg-gray-950 text-white text-center py-2 text-sm">
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
                        <span class="text-2xl font-bold text-amber-800 hidden sm:block">Quwwata Archery</span>
                        <span class="text-xl font-bold text-amber-800 sm:hidden">Quwwata</span>
                    </Link>
                </div>
                
                <!-- Search Bar - Hidden on mobile -->
                <div class="hidden lg:flex flex-1 max-w-2xl mx-8">
                    <div class="relative w-full">
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
                
                <!-- Desktop User Actions -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Account -->
                    <div class="relative" v-if="page.props.auth?.user">
                        <button
                            @click="toggleAccountMenu"
                            class="flex items-center space-x-1 text-gray-700 hover:text-amber-600"
                        >
                            <UserIcon class="h-6 w-6" />
                            <span class="hidden lg:block">My Account</span>
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
                        <Link href="/login" class="text-gray-700 hover:text-amber-600 text-sm lg:text-base">Login</Link>
                        <span class="text-gray-300">/</span>
                        <Link href="/register" class="text-gray-700 hover:text-amber-600 text-sm lg:text-base">Sign Up</Link>
                    </div>
                    
                    <!-- Cart -->
                    <button
                        @click="toggleCart"
                        class="relative flex items-center space-x-1 text-gray-700 hover:text-amber-600"
                    >
                        <ShoppingCartIcon class="h-6 w-6" />
                        <span class="hidden lg:block">Cart</span>
                        <span
                            v-if="cartCount > 0"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                        >
                            {{ cartCount }}
                        </span>
                    </button>
                </div>
                
                <!-- Mobile Actions -->
                <div class="flex md:hidden items-center space-x-3">
                    <!-- Search Icon for Mobile -->
                    <button
                        @click="toggleMobileSearch"
                        class="text-gray-700 hover:text-amber-600"
                    >
                        <SearchIcon class="h-6 w-6" />
                    </button>
                    
                    <!-- Cart Icon for Mobile -->
                    <button
                        @click="toggleCart"
                        class="relative text-gray-700 hover:text-amber-600"
                    >
                        <ShoppingCartIcon class="h-6 w-6" />
                        <span
                            v-if="cartCount > 0"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                        >
                            {{ cartCount }}
                        </span>
                    </button>
                    
                    <!-- Hamburger Menu Button -->
                    <button
                        @click="toggleMobileMenu"
                        class="text-gray-700 hover:text-amber-600 focus:outline-none"
                        :class="{ 'text-amber-600': showMobileMenu }"
                    >
                        <div class="w-6 h-6 flex flex-col justify-center items-center">
                            <span
                                class="bg-current h-0.5 w-6 transition-all duration-300 ease-in-out"
                                :class="showMobileMenu ? 'rotate-45 translate-y-1.5' : ''"
                            ></span>
                            <span
                                class="bg-current h-0.5 w-6 mt-1 transition-all duration-300 ease-in-out"
                                :class="showMobileMenu ? 'opacity-0' : ''"
                            ></span>
                            <span
                                class="bg-current h-0.5 w-6 mt-1 transition-all duration-300 ease-in-out"
                                :class="showMobileMenu ? '-rotate-45 -translate-y-1.5' : ''"
                            ></span>
                        </div>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Search Bar -->
            <div
                v-show="showMobileSearch"
                class="md:hidden pb-4 border-t border-gray-200 pt-4"
            >
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
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:block border-t border-gray-200 py-4">
                <ul class="flex items-center space-x-8">
                    <li>
                        <Link href="/" class="text-gray-700 hover:text-amber-600 font-medium">
                            Home
                        </Link>
                    </li>
                    <li class="relative group">
                        <button class="text-gray-700 hover:text-amber-600 font-medium flex items-center">
                            Traditional Bows
                            <ChevronDownIcon class="h-4 w-4 ml-1" />
                        </button>
                        <!-- Dropdown -->
                        <div class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <Link href="/products/bows" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Laminated Bows
                            </Link>
                            <Link href="/products/arrows" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Fiberglass Bows
                            </Link>
                        </div>
                    </li>
                    <li class="relative group">
                        <button class="text-gray-700 hover:text-amber-600 font-medium flex items-center">
                            Arrows
                            <ChevronDownIcon class="h-4 w-4 ml-1" />
                        </button>
                         <!-- Dropdown -->
                        <div class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg py-2 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <Link href="/products/bows" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Arrows
                            </Link>
                            <Link href="/products/arrows" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Quivers
                            </Link>
                            <Link href="/products/arrows" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Accessories
                            </Link>
                        </div>
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
        
        <!-- Mobile Menu -->
        <div
            v-show="showMobileMenu"
            class="md:hidden bg-white border-t border-gray-200 shadow-lg"
        >
            <div class="container mx-auto px-4 py-4">
                <!-- Mobile Navigation -->
                <nav class="space-y-4">
                    <Link
                        href="/"
                        @click="closeMobileMenu"
                        class="block text-gray-700 hover:text-amber-600 font-medium py-2"
                    >
                        Home
                    </Link>
                    
                    <!-- Traditional Bows Mobile -->
                    <div>
                        <button
                            @click="toggleMobileSubmenu('bows')"
                            class="w-full flex items-center justify-between text-gray-700 hover:text-amber-600 font-medium py-2"
                        >
                            Traditional Bows
                            <ChevronDownIcon
                                class="h-4 w-4 transition-transform duration-200"
                                :class="openMobileSubmenu === 'bows' ? 'rotate-180' : ''"
                            />
                        </button>
                        <div
                            v-show="openMobileSubmenu === 'bows'"
                            class="pl-4 space-y-2 mt-2"
                        >
                            <Link
                                href="/products/bows"
                                @click="closeMobileMenu"
                                class="block text-gray-600 hover:text-amber-600 py-1"
                            >
                                Laminated Bows
                            </Link>
                            <Link
                                href="/products/arrows"
                                @click="closeMobileMenu"
                                class="block text-gray-600 hover:text-amber-600 py-1"
                            >
                                Fiberglass Bows
                            </Link>
                        </div>
                    </div>
                    
                    <!-- Arrows Mobile -->
                    <div>
                        <button
                            @click="toggleMobileSubmenu('arrows')"
                            class="w-full flex items-center justify-between text-gray-700 hover:text-amber-600 font-medium py-2"
                        >
                            Arrows
                            <ChevronDownIcon
                                class="h-4 w-4 transition-transform duration-200"
                                :class="openMobileSubmenu === 'arrows' ? 'rotate-180' : ''"
                            />
                        </button>
                        <div
                            v-show="openMobileSubmenu === 'arrows'"
                            class="pl-4 space-y-2 mt-2"
                        >
                            <Link
                                href="/products/arrows"
                                @click="closeMobileMenu"
                                class="block text-gray-600 hover:text-amber-600 py-1"
                            >
                                Arrows
                            </Link>
                            <Link
                                href="/products/quivers"
                                @click="closeMobileMenu"
                                class="block text-gray-600 hover:text-amber-600 py-1"
                            >
                                Quivers
                            </Link>
                            <Link
                                href="/products/accessories"
                                @click="closeMobileMenu"
                                class="block text-gray-600 hover:text-amber-600 py-1"
                            >
                                Accessories
                            </Link>
                        </div>
                    </div>
                    
                    <Link
                        href="/about"
                        @click="closeMobileMenu"
                        class="block text-gray-700 hover:text-amber-600 font-medium py-2"
                    >
                        About Us
                    </Link>
                    <Link
                        href="/contact"
                        @click="closeMobileMenu"
                        class="block text-gray-700 hover:text-amber-600 font-medium py-2"
                    >
                        Contact
                    </Link>
                    
                    <!-- Mobile Account Section -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div v-if="page.props.auth?.user" class="space-y-2">
                            <Link
                                href="/profile"
                                @click="closeMobileMenu"
                                class="block text-gray-700 hover:text-amber-600 py-2"
                            >
                                My Profile
                            </Link>
                            <Link
                                href="/orders"
                                @click="closeMobileMenu"
                                class="block text-gray-700 hover:text-amber-600 py-2"
                            >
                                My Orders
                            </Link>
                            <Link
                                href="/logout"
                                method="post"
                                @click="closeMobileMenu"
                                class="block text-gray-700 hover:text-amber-600 py-2"
                            >
                                Logout
                            </Link>
                        </div>
                        <div v-else class="space-y-2">
                            <Link
                                href="/login"
                                @click="closeMobileMenu"
                                class="block text-gray-700 hover:text-amber-600 py-2"
                            >
                                Login
                            </Link>
                            <Link
                                href="/register"
                                @click="closeMobileMenu"
                                class="block text-gray-700 hover:text-amber-600 py-2"
                            >
                                Sign Up
                            </Link>
                        </div>
                    </div>
                </nav>
            </div>
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
const showMobileMenu = ref(false);
const showMobileSearch = ref(false);
const openMobileSubmenu = ref<string | null>(null);
const cartCount = ref(0); // This would come from your cart store/composable

// Methods
const handleSearch = () => {
    if (searchQuery.value.trim()) {
        // Navigate to search results
        window.location.href = `/search?q=${encodeURIComponent(searchQuery.value)}`;
        // Close mobile search after search
        showMobileSearch.value = false;
    }
};

const toggleAccountMenu = () => {
    showAccountMenu.value = !showAccountMenu.value;
};

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value;
    // Close other mobile menus when main menu is toggled
    if (showMobileMenu.value) {
        showMobileSearch.value = false;
        openMobileSubmenu.value = null;
    }
};

const toggleMobileSearch = () => {
    showMobileSearch.value = !showMobileSearch.value;
    // Close mobile menu when search is opened
    if (showMobileSearch.value) {
        showMobileMenu.value = false;
    }
};

const toggleMobileSubmenu = (menu: string) => {
    openMobileSubmenu.value = openMobileSubmenu.value === menu ? null : menu;
};

const closeMobileMenu = () => {
    showMobileMenu.value = false;
    openMobileSubmenu.value = null;
};

const toggleCart = () => {
    // Open cart sidebar or navigate to cart page
    console.log('Toggle cart');
};
</script>