<template>
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Carousel Slide
                </h2>
                <Link
                    :href="route('admin.carousel-slides.index')"
                    class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200"
                >
                    Back to Slides
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Title *
                                    </label>
                                    <input
                                        id="title"
                                        v-model="form.title"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                        placeholder="Enter slide title"
                                    />
                                    <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.title }}
                                    </div>
                                </div>

                                <!-- Badge -->
                                <div>
                                    <label for="badge" class="block text-sm font-medium text-gray-700 mb-2">
                                        Badge
                                    </label>
                                    <input
                                        id="badge"
                                        v-model="form.badge"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                        placeholder="e.g., New Season, Best Seller"
                                    />
                                    <div v-if="form.errors.badge" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.badge }}
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description *
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    required
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="Enter slide description"
                                ></textarea>
                                <div v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.description }}
                                </div>
                            </div>

                            <!-- Current Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Image
                                </label>
                                <div class="mb-4">
                                    <img
                                        :src="slide.image_url"
                                        :alt="slide.title"
                                        class="w-full max-w-md h-48 object-cover rounded-lg"
                                    />
                                </div>
                            </div>

                            <!-- New Image -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                    Replace Image
                                </label>
                                <input
                                    id="image"
                                    @change="handleImageChange"
                                    type="file"
                                    accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                />
                                <p class="mt-1 text-sm text-gray-500">
                                    Upload a new image (JPEG, PNG, JPG, GIF, WebP, max 2MB). Leave empty to keep current image.
                                </p>
                                <div v-if="form.errors.image" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.image }}
                                </div>
                                
                                <!-- New Image Preview -->
                                <div v-if="imagePreview" class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Image Preview</label>
                                    <img
                                        :src="imagePreview"
                                        alt="New Preview"
                                        class="w-full max-w-md h-48 object-cover rounded-lg"
                                    />
                                </div>

                                <!-- Remove Image Option -->
                                <div class="mt-4">
                                    <label class="flex items-center">
                                        <input
                                            v-model="form.remove_image"
                                            type="checkbox"
                                            class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Remove current image (use default)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="primary_button_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Primary Button Text *
                                    </label>
                                    <input
                                        id="primary_button_text"
                                        v-model="form.primary_button_text"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                        placeholder="e.g., Shop Now"
                                    />
                                    <div v-if="form.errors.primary_button_text" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.primary_button_text }}
                                    </div>
                                </div>

                                <div>
                                    <label for="primary_button_url" class="block text-sm font-medium text-gray-700 mb-2">
                                        Primary Button URL *
                                    </label>
                                    <input
                                        id="primary_button_url"
                                        v-model="form.primary_button_url"
                                        type="url"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                        placeholder="/products"
                                    />
                                    <div v-if="form.errors.primary_button_url" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.primary_button_url }}
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="secondary_button_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Secondary Button Text
                                    </label>
                                    <input
                                        id="secondary_button_text"
                                        v-model="form.secondary_button_text"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                        placeholder="e.g., Learn More"
                                    />
                                    <div v-if="form.errors.secondary_button_text" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.secondary_button_text }}
                                    </div>
                                </div>

                                <div>
                                    <label for="secondary_button_url" class="block text-sm font-medium text-gray-700 mb-2">
                                        Secondary Button URL
                                    </label>
                                    <input
                                        id="secondary_button_url"
                                        v-model="form.secondary_button_url"
                                        type="url"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                        placeholder="/about"
                                    />
                                    <div v-if="form.errors.secondary_button_url" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.secondary_button_url }}
                                    </div>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sort Order
                                    </label>
                                    <input
                                        id="sort_order"
                                        v-model="form.sort_order"
                                        type="number"
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500"
                                        placeholder="0"
                                    />
                                    <div v-if="form.errors.sort_order" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.sort_order }}
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <input
                                        id="is_active"
                                        v-model="form.is_active"
                                        type="checkbox"
                                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded"
                                    />
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Active (show on website)
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <Link
                                    :href="route('admin.carousel-slides.index')"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-6 rounded-lg transition-colors duration-200"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="bg-amber-600 hover:bg-amber-700 disabled:bg-amber-300 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200"
                                >
                                    <span v-if="form.processing">Updating...</span>
                                    <span v-else>Update Slide</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    sort_order: number;
    is_active: boolean;
}

const props = defineProps<{
    slide: CarouselSlide;
}>();

const imagePreview = ref<string | null>(null);

const form = useForm({
    title: props.slide.title,
    description: props.slide.description,
    badge: props.slide.badge || '',
    image: null as File | null,
    primary_button_text: props.slide.primary_button_text,
    primary_button_url: props.slide.primary_button_url,
    secondary_button_text: props.slide.secondary_button_text || '',
    secondary_button_url: props.slide.secondary_button_url || '',
    sort_order: props.slide.sort_order,
    is_active: props.slide.is_active,
    remove_image: false,
});

const handleImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        form.image = file;
        // Automatically uncheck remove_image when selecting a new image
        form.remove_image = false;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    // Debug: Log the form data before submission
    console.log('Form data before submission:', {
        title: form.title,
        description: form.description,
        badge: form.badge,
        image: form.image,
        primary_button_text: form.primary_button_text,
        primary_button_url: form.primary_button_url,
        secondary_button_text: form.secondary_button_text,
        secondary_button_url: form.secondary_button_url,
        sort_order: form.sort_order,
        is_active: form.is_active,
        remove_image: form.remove_image,
    });
    
    // Use POST with _method=PUT for file uploads (Laravel method spoofing)
    form.transform((data) => ({
        ...data,
        _method: 'PUT'
    })).post(route('admin.carousel-slides.update', props.slide.id), {
        preserveScroll: true,
    });
};
</script>