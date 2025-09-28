<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { request } from '@/routes/password';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Target, ArrowLeft, Shield, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('admin.login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <Head title="Log in" />
    
    <div class="min-h-screen flex">
        <!-- Left side - Login Form -->
        <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white">
            <div class="mx-auto w-full max-w-md lg:w-96">
                <!-- Back to home button -->
                <div class="mb-8">
                    <Link 
                        href="/" 
                        class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-amber-600 transition-colors"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back to Quwwata
                    </Link>
                </div>

                <!-- Logo and header -->
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center mb-6">
                        <div class="p-3 bg-amber-100 rounded-xl">
                            <Target class="h-8 w-8 text-amber-600" />
                        </div>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Welcome back
                    </h2>
                    <p class="text-gray-600">
                        Sign in to manage your archery products
                    </p>
                </div>

                <!-- Status message -->
                <div
                    v-if="status"
                    class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg"
                >
                    <div class="flex items-center">
                        <Shield class="h-5 w-5 text-green-600 mr-2" />
                        <span class="text-sm font-medium text-green-800">{{ status }}</span>
                    </div>
                </div>

                <!-- Login Form -->
                                <!-- Login Form -->
                <form
                    @submit.prevent="submit"
                    class="space-y-6"
                >
                    <div>
                        <Label 
                            for="email" 
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Email address
                        </Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            name="email"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="email"
                            placeholder="archer@example.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                        />
                        <InputError :message="form.errors.email" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <Label 
                                for="password" 
                                class="block text-sm font-semibold text-gray-700"
                            >
                                Password
                            </Label>
                            <TextLink
                                v-if="canResetPassword"
                                :href="request()"
                                class="text-sm font-medium text-amber-600 hover:text-amber-500 transition-colors"
                                :tabindex="5"
                            >
                                Forgot password?
                            </TextLink>
                        </div>
                        <div class="relative">
                            <Input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                :tabindex="2"
                                autocomplete="current-password"
                                placeholder="Enter your password"
                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-colors"
                            />
                            <button
                                type="button"
                                @click="togglePasswordVisibility"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors"
                                :tabindex="-1"
                            >
                                <Eye v-if="!showPassword" class="h-5 w-5" />
                                <EyeOff v-else class="h-5 w-5" />
                            </button>
                        </div>
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <Label for="remember" class="flex items-center cursor-pointer">
                            <Checkbox 
                                id="remember" 
                                v-model="form.remember"
                                name="remember" 
                                :tabindex="3"
                                class="mr-3 text-amber-600 focus:ring-amber-500" 
                            />
                            <span class="text-sm text-gray-700 font-medium">Remember me</span>
                        </Label>
                    </div>

                    <Button
                        type="submit"
                        class="w-full bg-amber-600 hover:bg-amber-700 focus:ring-amber-500 text-white font-semibold py-3 px-4 rounded-lg transition-colors shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98]"
                        :tabindex="4"
                        :disabled="form.processing"
                        data-test="login-button"
                    >
                        <LoaderCircle
                            v-if="form.processing"
                            class="mr-2 h-5 w-5 animate-spin"
                        />
                        <span v-if="form.processing">Signing in...</span>
                        <span v-else">Sign in to Dashboard</span>
                    </Button>
                </form>
            </div>
        </div>

        <!-- Right side - Hero Image/Branding -->
        <div class="hidden lg:block relative flex-1 bg-gradient-to-br from-amber-600 via-amber-700 to-amber-800">
            <!-- Background Pattern -->
            <div class="absolute inset-0 bg-black/20"></div>
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Ccircle cx=\'30\' cy=\'30\' r=\'2\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
            
            <!-- Content -->
            <div class="relative h-full flex flex-col justify-center items-center p-12 text-white">
                <div class="text-center max-w-md">
                    <!-- Large Logo/Icon -->
                    <div class="mb-8">
                        <div class="mx-auto w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                            <Target class="w-12 h-12 text-white" />
                        </div>
                    </div>
                    
                    <!-- Main Message -->
                    <h1 class="text-4xl font-bold mb-4 leading-tight">
                        Traditional Archery Supply
                    </h1>
                    <p class="text-xl text-amber-100 mb-8 leading-relaxed">
                        Manage your archery products and categories with ease. 
                        Your gateway to traditional archery excellence.
                    </p>
                    
                    <!-- Features -->
                    <div class="space-y-4 text-left">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mt-0.5">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                            <span class="text-amber-100">Product & Category Management</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mt-0.5">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                            <span class="text-amber-100">Image Gallery Management</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mt-0.5">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                            <span class="text-amber-100">Inventory & Stock Control</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom decoration -->
                <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-black/30 to-transparent"></div>
            </div>
        </div>
    </div>
</template>
