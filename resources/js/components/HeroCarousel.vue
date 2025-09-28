<template>
    <section class="relative min-h-[600px] md:h-[700px] overflow-hidden bg-gradient-to-r from-amber-50 to-amber-100">
        <!-- Carousel Container -->
        <div 
            class="relative w-full h-full"
            @touchstart="handleTouchStart"
            @touchend="handleTouchEnd"
        >
            <!-- Slides -->
            <div
                v-for="(slide, index) in slides"
                :key="slide.id"
                :class="[
                    'absolute inset-0 transition-all duration-500 ease-in-out',
                    index === currentSlide ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full'
                ]"
            >
                <div class="container mx-auto px-4 h-full flex items-center">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-12 items-center w-full py-12 sm:py-16 lg:py-0">
                        <!-- Content -->
                        <div class="space-y-6 text-center lg:text-left order-2 lg:order-1 px-4 lg:px-0">
                            <div class="space-y-4">
                                <span class="inline-block bg-amber-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                    {{ slide.badge }}
                                </span>
                                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold text-gray-900 leading-tight">
                                    {{ slide.title }}
                                </h1>
                            </div>
                            <p class="text-sm sm:text-base md:text-lg lg:text-xl text-gray-700 max-w-lg mx-auto lg:mx-0 leading-relaxed">
                                {{ slide.description }}
                            </p>
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start pt-2">
                                <Link
                                    :href="slide.primaryButton.href"
                                    class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3.5 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2 text-sm sm:text-base"
                                >
                                    <span>{{ slide.primaryButton.text }}</span>
                                    <ArrowRightIcon class="h-4 w-4 sm:h-5 sm:w-5" />
                                </Link>
                                <Link
                                    v-if="slide.secondaryButton"
                                    :href="slide.secondaryButton.href"
                                    class="border-2 border-amber-600 text-amber-600 hover:bg-amber-600 hover:text-white font-semibold py-3.5 px-6 rounded-lg transition-colors duration-200 text-center text-sm sm:text-base"
                                >
                                    {{ slide.secondaryButton.text }}
                                </Link>
                            </div>
                        </div>
                        
                        <!-- Image -->
                        <div class="relative order-1 lg:order-2 px-4 lg:px-0">
                            <div class="aspect-[4/3] sm:aspect-square lg:aspect-[4/3] relative overflow-hidden rounded-2xl max-w-sm sm:max-w-md mx-auto lg:max-w-none shadow-2xl">
                                <img
                                    :src="slide.image"
                                    :alt="slide.title"
                                    class="w-full h-full object-cover"
                                />
                                <!-- Overlay for better contrast -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent"></div>
                            </div>
                            
                            <!-- Floating Elements -->
                            <div class="absolute -top-4 -right-4 w-16 sm:w-20 h-16 sm:h-20 bg-amber-200 rounded-full blur-xl opacity-70"></div>
                            <div class="absolute -bottom-4 -left-4 w-12 sm:w-16 h-12 sm:h-16 bg-amber-300 rounded-full blur-lg opacity-60"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation Dots -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button
                v-for="(slide, index) in slides"
                :key="`dot-${slide.id}`"
                @click="goToSlide(index)"
                :class="[
                    'w-3 h-3 rounded-full transition-all duration-300',
                    index === currentSlide 
                        ? 'bg-white shadow-lg scale-110' 
                        : 'bg-white/50 hover:bg-white/70'
                ]"
                :aria-label="`Go to slide ${index + 1}`"
            ></button>
        </div>
        
        <!-- Navigation Arrows - Hidden on mobile, visible on tablet+ -->
        <button
            @click="prevSlide"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200 hover:scale-110 hidden md:block"
            aria-label="Previous slide"
        >
            <ChevronLeftIcon class="h-6 w-6" />
        </button>
        <button
            @click="nextSlide"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 p-2 rounded-full shadow-lg transition-all duration-200 hover:scale-110 hidden md:block"
            aria-label="Next slide"
        >
            <ChevronRightIcon class="h-6 w-6" />
        </button>
    </section>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import {
    ArrowRightIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
} from 'lucide-vue-next';

interface SlideButton {
    text: string;
    href: string;
}

interface Slide {
    id: number;
    title: string;
    description: string;
    badge: string;
    image: string;
    primaryButton: SlideButton;
    secondaryButton?: SlideButton;
}

interface Props {
    slides?: Slide[];
    autoPlay?: boolean;
    autoPlayInterval?: number;
}

const props = withDefaults(defineProps<Props>(), {
    autoPlay: true,
    autoPlayInterval: 5000,
    slides: () => [
        {
            id: 1,
            badge: 'New Collection',
            title: 'Traditional Ottoman Bows',
            description: 'Experience the power and precision of authentic Ottoman archery with our handcrafted traditional bows.',
            image: '/images/hero-bow-1.jpg',
            primaryButton: {
                text: 'Shop Now',
                href: '/products/bows'
            },
            secondaryButton: {
                text: 'Learn More',
                href: '/about'
            }
        },
        {
            id: 2,
            badge: 'Premium Quality',
            title: 'Historical Clothing',
            description: 'Feel the history on yourself with our authentic historical clothing collection.',
            image: '/images/hero-clothing.jpg',
            primaryButton: {
                text: 'Explore Collection',
                href: '/products/historical-clothes'
            }
        },
        {
            id: 3,
            badge: 'Professional Grade',
            title: 'Equestrian Equipment',
            description: 'Complete your horseback archery experience with our professional-grade equipment.',
            image: '/images/hero-equestrian.jpg',
            primaryButton: {
                text: 'View Equipment',
                href: '/products/equestrian'
            }
        }
    ]
});

// State
const currentSlide = ref(0);
let autoPlayTimer: number | null = null;

// Methods
const goToSlide = (index: number) => {
    currentSlide.value = index;
    resetAutoPlay();
};

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % props.slides.length;
    resetAutoPlay();
};

const prevSlide = () => {
    currentSlide.value = currentSlide.value === 0 ? props.slides.length - 1 : currentSlide.value - 1;
    resetAutoPlay();
};

const startAutoPlay = () => {
    if (props.autoPlay && props.slides.length > 1) {
        autoPlayTimer = setInterval(() => {
            nextSlide();
        }, props.autoPlayInterval);
    }
};

const stopAutoPlay = () => {
    if (autoPlayTimer) {
        clearInterval(autoPlayTimer);
        autoPlayTimer = null;
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