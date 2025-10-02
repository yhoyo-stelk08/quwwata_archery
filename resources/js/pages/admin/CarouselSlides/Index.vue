<template>
    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Carousel Slides
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">Manage your hero carousel slides</p>
                </div>
                <Link
                    :href="route('admin.carousel-slides.create')"
                    class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
                >
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Slide
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="slides.length === 0" class="text-center py-12">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No carousel slides</h3>
                            <p class="text-gray-500 mb-4">Get started by creating your first carousel slide.</p>
                            <Link
                                :href="route('admin.carousel-slides.create')"
                                class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add New Slide
                            </Link>
                        </div>

                        <div v-else class="overflow-hidden">
                            <!-- Add button above content -->
                            <div class="flex justify-end px-6 py-4 border-b border-gray-200">
                                <Link
                                    :href="route('admin.carousel-slides.create')"
                                    class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 flex items-center"
                                >
                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add New Slide
                                </Link>
                            </div>
                            
                            <!-- Desktop Table View (hidden on mobile) -->
                            <div class="hidden md:block">
                                <!-- Table Header -->
                                <div class="grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="col-span-4">SLIDE</div>
                                    <div class="col-span-2">BUTTONS</div>
                                    <div class="col-span-2">STATUS</div>
                                    <div class="col-span-2">SORT ORDER</div>
                                    <div class="col-span-2">ACTIONS</div>
                                </div>
                                
                                <!-- Table Rows -->
                                <div class="divide-y divide-gray-200">
                                    <div
                                        v-for="slide in slides"
                                        :key="slide.id"
                                        class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50 transition-colors duration-200"
                                    >
                                        <!-- Slide Info -->
                                        <div class="col-span-4 flex items-start space-x-3">
                                            <img
                                                :src="slide.image_url"
                                                :alt="slide.title"
                                                class="w-16 h-12 object-cover rounded flex-shrink-0"
                                            />
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-1">
                                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ slide.title }}</h3>
                                                    <span v-if="slide.badge" class="inline-block bg-amber-100 text-amber-800 text-xs font-semibold px-2 py-1 rounded">
                                                        {{ slide.badge }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-500 line-clamp-2">{{ slide.description }}</p>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="col-span-2 flex flex-col justify-center space-y-1">
                                            <div class="text-xs text-gray-900 font-medium">{{ slide.primary_button_text }}</div>
                                            <div v-if="slide.secondary_button_text" class="text-xs text-gray-500">{{ slide.secondary_button_text }}</div>
                                            <div v-else class="text-xs text-gray-400">—</div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-span-2 flex items-center">
                                            <span
                                                :class="[
                                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                                    slide.is_active
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800'
                                                ]"
                                            >
                                                {{ slide.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <!-- Sort Order -->
                                        <div class="col-span-2 flex items-center">
                                            <span class="text-sm text-gray-900">{{ slide.sort_order }}</span>
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-span-2 flex items-center space-x-3">
                                            <Link
                                                :href="route('admin.carousel-slides.edit', slide.id)"
                                                class="text-amber-600 hover:text-amber-900 text-sm font-medium"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                @click="deleteSlide(slide)"
                                                class="text-red-600 hover:text-red-900 text-sm font-medium"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Card View (visible on mobile only) -->
                            <div class="md:hidden space-y-3 p-4">
                                <div
                                    v-for="slide in slides"
                                    :key="`mobile-${slide.id}`"
                                    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                                >
                                    <div 
                                        class="relative h-40 bg-cover bg-center bg-no-repeat"
                                        :style="{ backgroundImage: `url(${slide.image_url})` }"
                                    >
                                        <!-- Overlay for better text readability -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                        
                                        <!-- Status Badge Overlay -->
                                        <div class="absolute top-3 right-3 z-10">
                                            <span
                                                :class="[
                                                    'inline-flex px-3 py-1 text-xs font-semibold rounded-full backdrop-blur-sm',
                                                    slide.is_active
                                                        ? 'bg-green-100/90 text-green-800'
                                                        : 'bg-red-100/90 text-red-800'
                                                ]"
                                            >
                                                {{ slide.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <!-- Badge Overlay -->
                                        <div v-if="slide.badge" class="absolute bottom-3 left-3 z-10">
                                            <span class="inline-block bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                                {{ slide.badge }}
                                            </span>
                                        </div>

                                        <!-- Title overlay on image -->
                                        <div class="absolute bottom-3 right-3 z-10">
                                            <h3 class="font-semibold text-white text-sm drop-shadow-lg">{{ slide.title }}</h3>
                                        </div>
                                    </div>
                                    
                                    <!-- Mobile Card Content -->
                                    <div class="p-4">
                                        <!-- Description -->
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ slide.description }}</p>
                                        
                                        <!-- Buttons Row -->
                                        <div class="flex items-center justify-between text-sm mb-4">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-gray-700 font-medium truncate">{{ slide.primary_button_text }}</div>
                                                <div v-if="slide.secondary_button_text" class="text-gray-500 text-xs truncate">{{ slide.secondary_button_text }}</div>
                                            </div>
                                            <div class="text-xs text-gray-400 ml-3">
                                                Order {{ slide.sort_order }}
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex space-x-3">
                                            <Link
                                                :href="route('admin.carousel-slides.edit', slide.id)"
                                                class="flex-1 bg-amber-50 hover:bg-amber-100 text-amber-700 text-center py-2.5 px-4 rounded-lg font-medium transition-colors duration-200"
                                            >
                                                Edit Slide
                                            </Link>
                                            <button
                                                @click="deleteSlide(slide)"
                                                class="flex-1 bg-red-50 hover:bg-red-100 text-red-700 py-2.5 px-4 rounded-lg font-medium transition-colors duration-200"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div 
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                    @click="showDeleteModal = false"
                ></div>
                
                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <!-- Modal panel -->
                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full z-50">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete Carousel Slide
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete "{{ slideToDelete?.title }}"? 
                                        This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="button"
                            @click="confirmDelete"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Delete
                        </button>
                        <button
                            type="button"
                            @click="showDeleteModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

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
    created_at: string;
}

defineProps<{
    slides: CarouselSlide[];
}>();

const showDeleteModal = ref(false);
const slideToDelete = ref<CarouselSlide | null>(null);

const deleteSlide = (slide: CarouselSlide) => {
    slideToDelete.value = slide;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (slideToDelete.value) {
        router.delete(route('admin.carousel-slides.destroy', slideToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false;
                slideToDelete.value = null;
            },
            onError: (errors) => {
                console.error('Delete failed:', errors);
            },
        });
    }
};
</script>