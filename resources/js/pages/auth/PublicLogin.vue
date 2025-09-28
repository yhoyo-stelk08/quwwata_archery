<template>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 to-amber-100">
        <div class="flex min-h-screen">
            <!-- Left side - Hero Section -->
            <div class="hidden lg:flex lg:flex-1 lg:flex-col lg:justify-center lg:items-center bg-gradient-to-br from-amber-600 to-amber-800 text-white relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-20 left-20 w-32 h-32 border-2 border-white rounded-full"></div>
                    <div class="absolute bottom-20 right-20 w-24 h-24 border-2 border-white rounded-full"></div>
                    <div class="absolute top-1/2 left-1/4 w-16 h-16 border-2 border-white rounded-full"></div>
                </div>
                
                <div class="z-10 text-center px-12">
                    <!-- Logo -->
                    <div class="flex items-center justify-center mb-8">
                        <div class="p-4 bg-white/10 backdrop-blur-sm rounded-2xl">
                            <Target class="h-16 w-16 text-white" />
                        </div>
                    </div>
                    
                    <h1 class="text-4xl font-bold mb-4">
                        Welcome to Quwwata
                    </h1>
                    <p class="text-xl mb-8 text-amber-100">
                        Traditional Archery Supply
                    </p>
                    
                    <div class="space-y-4 text-left">
                        <div class="flex items-center">
                            <ShieldCheck class="h-5 w-5 mr-3 text-amber-200" />
                            <span>Premium quality traditional bows</span>
                        </div>
                        <div class="flex items-center">
                            <ShieldCheck class="h-5 w-5 mr-3 text-amber-200" />
                            <span>Expert craftsmanship since decades</span>
                        </div>
                        <div class="flex items-center">
                            <ShieldCheck class="h-5 w-5 mr-3 text-amber-200" />
                            <span>Worldwide shipping available</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side - Login Form -->
            <div class="flex-1 flex flex-col justify-center px-6 py-12 lg:px-16 xl:px-20">
                <div class="mx-auto w-full max-w-md">
                    <!-- Back to home button -->
                    <div class="mb-8">
                        <Link 
                            href="/" 
                            class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-amber-600 transition-colors group"
                        >
                            <ArrowLeft class="mr-2 h-4 w-4 group-hover:-translate-x-1 transition-transform" />
                            Back to Store
                        </Link>
                    </div>

                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center justify-center lg:hidden mb-6">
                            <div class="p-3 bg-amber-100 rounded-xl">
                                <Target class="h-8 w-8 text-amber-600" />
                            </div>
                        </div>
                        
                        <h2 class="text-3xl font-bold text-gray-900 mb-2">
                            Sign in to your account
                        </h2>
                        <p class="text-gray-600">
                            Access your orders, wishlist, and account settings
                        </p>
                    </div>

                    <!-- Login Form -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Email -->
                        <div>
                            <Label for="email" class="text-sm font-medium text-gray-700">
                                Email address
                            </Label>
                            <div class="mt-1 relative">
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Enter your email"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <Label for="password" class="text-sm font-medium text-gray-700">
                                Password
                            </Label>
                            <div class="mt-1 relative">
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    autocomplete="current-password"
                                    required
                                    class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Enter your password"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    <Eye v-if="!showPassword" class="h-5 w-5" />
                                    <EyeOff v-else class="h-5 w-5" />
                                </button>
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input
                                    v-model="form.remember"
                                    type="checkbox"
                                    class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                >
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>

                            <Link
                                href="/forgot-password"
                                class="text-sm text-amber-600 hover:text-amber-500 font-medium"
                            >
                                Forgot password?
                            </Link>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <LoaderCircle v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4" />
                            {{ form.processing ? 'Signing in...' : 'Sign in' }}
                        </button>

                        <!-- Register Link -->
                        <div class="text-center pt-4 border-t border-gray-200">
                            <p class="text-sm text-gray-600">
                                Don't have an account?
                                <Link
                                    href="/register"
                                    class="font-medium text-amber-600 hover:text-amber-500 transition-colors"
                                >
                                    Create one here
                                </Link>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ArrowLeft, Eye, EyeOff, LoaderCircle, ShieldCheck, Target } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';

defineOptions({
    layout: false,
});

const props = defineProps<{
    name: string;
    quote: { message: string; author: string };
    canResetPassword?: boolean;
    status?: string;
    errors: Record<string, string>;
    auth?: {
        user: any;
    };
}>();

// Redirect to home if already authenticated
onMounted(() => {
    if (props.auth?.user) {
        router.visit('/');
    }
});

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => {
            form.reset('password');
        },
    });
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>