<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Left side -->
                    <div class="flex items-center">
                        <!-- Logo -->
                        <Link href="/" class="flex items-center space-x-2">
                            <Target class="h-8 w-8 text-amber-600" />
                            <span class="text-xl font-bold text-gray-900">Quwwata Admin</span>
                        </Link>

                        <!-- Navigation Links -->
                        <div class="hidden md:ml-10 md:flex md:space-x-8">
                            <Link
                                :href="route('admin.dashboard')"
                                :class="[
                                    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                                    isCurrentRoute('admin.dashboard')
                                        ? 'border-amber-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                ]"
                            >
                                <LayoutDashboard class="h-4 w-4 mr-2" />
                                Dashboard
                            </Link>

                            <Link
                                :href="route('admin.products.index')"
                                :class="[
                                    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                                    isCurrentRoute('admin.products.*')
                                        ? 'border-amber-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                ]"
                            >
                                <Package class="h-4 w-4 mr-2" />
                                Products
                            </Link>

                            <Link
                                :href="route('admin.categories.index')"
                                :class="[
                                    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                                    isCurrentRoute('admin.categories.*')
                                        ? 'border-amber-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                ]"
                            >
                                <FolderOpen class="h-4 w-4 mr-2" />
                                Categories
                            </Link>

                            <Link
                                :href="route('admin.product-images.index')"
                                :class="[
                                    'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors',
                                    isCurrentRoute('admin.product-images.*')
                                        ? 'border-amber-500 text-gray-900'
                                        : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'
                                ]"
                            >
                                <Image class="h-4 w-4 mr-2" />
                                Images
                            </Link>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <!-- View Site Link -->
                        <Link
                            href="/"
                            class="text-gray-500 hover:text-gray-700 text-sm font-medium flex items-center"
                        >
                            <ExternalLink class="h-4 w-4 mr-1" />
                            View Site
                        </Link>

                        <!-- User Menu -->
                        <div class="relative">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                                        <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                            <User class="h-5 w-5 text-amber-600" />
                                        </div>
                                        <span class="ml-2 text-gray-700 font-medium">{{ $page.props.auth.user.name }}</span>
                                        <ChevronDown class="ml-1 h-4 w-4 text-gray-400" />
                                    </button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-48">
                                    <DropdownMenuItem as-child>
                                        <Link :href="route('profile.edit')" class="flex items-center">
                                            <Settings class="mr-2 h-4 w-4" />
                                            Profile
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem as-child>
                                        <Link
                                            :href="route('admin.logout')"
                                            method="post"
                                            class="flex items-center text-red-600"
                                        >
                                            <LogOut class="mr-2 h-4 w-4" />
                                            Logout
                                        </Link>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>

                        <!-- Mobile menu button -->
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100"
                        >
                            <Menu v-if="!mobileMenuOpen" class="h-6 w-6" />
                            <X v-else class="h-6 w-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-show="mobileMenuOpen" class="md:hidden">
                <div class="pt-2 pb-3 space-y-1 bg-white border-t">
                    <Link
                        :href="route('admin.dashboard')"
                        :class="[
                            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium',
                            isCurrentRoute('admin.dashboard')
                                ? 'bg-amber-50 border-amber-500 text-amber-700'
                                : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
                        ]"
                        @click="mobileMenuOpen = false"
                    >
                        Dashboard
                    </Link>
                    <Link
                        :href="route('admin.products.index')"
                        :class="[
                            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium',
                            isCurrentRoute('admin.products.*')
                                ? 'bg-amber-50 border-amber-500 text-amber-700'
                                : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
                        ]"
                        @click="mobileMenuOpen = false"
                    >
                        Products
                    </Link>
                    <Link
                        :href="route('admin.categories.index')"
                        :class="[
                            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium',
                            isCurrentRoute('admin.categories.*')
                                ? 'bg-amber-50 border-amber-500 text-amber-700'
                                : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
                        ]"
                        @click="mobileMenuOpen = false"
                    >
                        Categories
                    </Link>
                    <Link
                        :href="route('admin.product-images.index')"
                        :class="[
                            'block pl-3 pr-4 py-2 border-l-4 text-base font-medium',
                            isCurrentRoute('admin.product-images.*')
                                ? 'bg-amber-50 border-amber-500 text-amber-700'
                                : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
                        ]"
                        @click="mobileMenuOpen = false"
                    >
                        Images
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Toast Notifications -->
        <div
            v-if="(page.props.flash as any)?.success"
            class="fixed bottom-4 right-4 z-50"
        >
            <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <CheckCircle class="h-5 w-5 mr-2" />
                    {{ (page.props.flash as any)?.success }}
                </div>
            </div>
        </div>

        <div
            v-if="(page.props.flash as any)?.error"
            class="fixed bottom-4 right-4 z-50"
        >
            <div class="bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <AlertCircle class="h-5 w-5 mr-2" />
                    {{ (page.props.flash as any)?.error }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Target,
    LayoutDashboard,
    Package,
    FolderOpen,
    Image,
    ExternalLink,
    User,
    Settings,
    LogOut,
    Menu,
    X,
    ChevronDown,
    CheckCircle,
    AlertCircle,
} from 'lucide-vue-next';

const mobileMenuOpen = ref(false);
const page = usePage();

const isCurrentRoute = (routeName: string): boolean => {
    const currentRoute = page.props.route as string | undefined;
    if (routeName.endsWith('.*')) {
        const baseRoute = routeName.slice(0, -2);
        return currentRoute?.startsWith(baseRoute) || false;
    }
    return currentRoute === routeName;
};
</script>