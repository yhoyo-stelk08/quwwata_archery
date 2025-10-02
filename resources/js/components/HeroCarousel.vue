<!-- filepath: resources/js/components/HeroCarouselNew.vue -->
<template>
    <section class="relative h-[600px] md:h-[700px] overflow-hidden">
        <!-- Loading State -->
        <div v-if="slides.length === 0" class="absolute inset-0 flex items-center justify-center bg-gradient-to-r from-amber-50 to-amber-100">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-amber-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading slides...</p>
            </div>
        </div>

        <!-- Carousel Container -->
        <div 
            v-else
            class="relative w-full h-full min-h-[600px] md:min-h-[700px]"
            @touchstart="handleTouchStart"
            @touchend="handleTouchEnd"
        >
            <!-- Slides -->
            <div
                v-for="(slide, index) in slides"
                :key="slide.id"
                :class="[
                    'absolute inset-0 transition-opacity duration-1000',
                    currentSlide === index ? 'opacity-100' : 'opacity-0'
                ]"
            >
                <!-- Full-width background image with mobile fallback -->
                <div class="absolute inset-0">
                    <!-- Main image element for better mobile support -->
                    <img
                        :src="slide.image_url"
                        :alt="slide.title"
                        class="w-full h-full object-cover"
                        @error="handleImageError($event, slide)"
                        @load="handleImageLoad($event, slide)"
                    />
                    <!-- Dark overlay for better text readability -->
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>

                <!-- Content overlay -->
                <div class="relative z-10 container mx-auto px-4 h-full flex items-center justify-center">
                    <div class="max-w-2xl text-center w-full flex flex-col items-center">
                        <div class="space-y-6">
                            <div class="space-y-4">
                                <span 
                                    v-if="slide.badge"
                                    class="inline-block bg-amber-600 text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg"
                                >
                                    {{ slide.badge }}
                                </span>
                                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-white leading-tight drop-shadow-lg">
                                    {{ slide.title }}
                                </h1>
                            </div>
                            <p class="text-lg sm:text-xl md:text-2xl text-white/90 max-w-xl mx-auto leading-relaxed drop-shadow-md">
                                {{ slide.description }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
                                <Link
                                    :href="slide.primary_button_url"
                                    class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-4 px-8 rounded-lg transition-all duration-200 flex items-center justify-center space-x-2 text-base sm:text-lg shadow-xl hover:shadow-2xl transform hover:scale-105"
                                >
                                    <span>{{ slide.primary_button_text }}</span>
                                    <ArrowRightIcon class="h-5 w-5" />
                                </Link>
                                <Link
                                    v-if="slide.secondary_button_text && slide.secondary_button_url"
                                    :href="slide.secondary_button_url"
                                    class="border-2 border-white text-white hover:bg-white hover:text-gray-900 font-semibold py-4 px-8 rounded-lg transition-all duration-200 text-center text-base sm:text-lg shadow-xl backdrop-blur-sm"
                                >
                                    {{ slide.secondary_button_text }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fallback when image fails to load -->
                <div 
                    v-if="slide.imageError" 
                    class="absolute inset-0 bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center z-5"
                >
                    <div class="text-center p-6">
                        <div class="w-16 h-16 mx-auto mb-4 bg-amber-300 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 002 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-amber-700 font-medium">{{ slide.title }}</p>
                        <p class="text-amber-600 text-sm">Image unavailable</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Dots -->
        <div v-if="slides.length > 1" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-20">
            <button
                v-for="(slide, index) in slides"
                :key="`dot-${slide.id}`"
                @click="goToSlide(index)"
                :class="[
                    'w-3 h-3 rounded-full transition-all duration-200',
                    currentSlide === index
                        ? 'bg-white scale-110'
                        : 'bg-white/50 hover:bg-white/75'
                ]"
            ></button>
        </div>

        <!-- Navigation Arrows -->
        <button
            v-if="slides.length > 1"
            @click="prevSlide"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-200 hover:scale-110 z-20 hidden md:block"
            aria-label="Previous slide"
        >
            <ChevronLeftIcon class="h-6 w-6" />
        </button>
        <button
            v-if="slides.length > 1"
            @click="nextSlide"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-200 hover:scale-110 z-20 hidden md:block"
            aria-label="Next slide"
        >
            <ChevronRightIcon class="h-6 w-6" />
        </button>
    </section>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRightIcon, ChevronLeftIcon, ChevronRightIcon } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';

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
    imageError?: boolean;
}

const props = defineProps<{
    slides: CarouselSlide[];
}>();

const currentSlide = ref(0);
let autoplayInterval: number | null = null;

// Image error handling
const handleImageError = (event: Event, slide: CarouselSlide) => {
    console.error('Failed to load image:', slide.image_url);
    slide.imageError = true;
};

const handleImageLoad = (event: Event, slide: CarouselSlide) => {
    console.log('Image loaded successfully:', slide.image_url);
    slide.imageError = false;
    const img = event.target as HTMLImageElement;
    img.style.display = 'block';
};

// Slide navigation
const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % props.slides.length;
    resetAutoPlay();
};

const prevSlide = () => {
    currentSlide.value = currentSlide.value === 0 ? props.slides.length - 1 : currentSlide.value - 1;
    resetAutoPlay();
};

const goToSlide = (index: number) => {
    currentSlide.value = index;
    resetAutoPlay();
};

// Auto-play functionality
const startAutoPlay = () => {
    if (props.slides.length <= 1) return;
    
    autoplayInterval = setInterval(() => {
        nextSlide();
    }, 5000) as number;
};

const stopAutoPlay = () => {
    if (autoplayInterval) {
        clearInterval(autoplayInterval);
        autoplayInterval = null;
    }
};

const resetAutoPlay = () => {
    stopAutoPlay();
    startAutoPlay();
};

// Touch/Swipe support
let touchStartX = 0;
let touchEndX = 0;

const handleTouchStart = (e: TouchEvent) => {
    touchStartX = e.changedTouches[0].screenX;
};

const handleTouchEnd = (e: TouchEvent) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipeGesture();
};

const handleSwipeGesture = () => {
    const swipeThreshold = 50;
    const swipeDistance = touchEndX - touchStartX;
    
    if (Math.abs(swipeDistance) > swipeThreshold) {
        if (swipeDistance > 0) {
            prevSlide();
        } else {
            nextSlide();
        }
    }
};

// Lifecycle
onMounted(() => {
    startAutoPlay();
});

onUnmounted(() => {
    stopAutoPlay();
});
</script>