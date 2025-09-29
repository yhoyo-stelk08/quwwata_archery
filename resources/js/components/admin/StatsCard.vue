<template>
    <div :class="cardClasses">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div :class="iconClasses">
                    <component :is="iconComponent" class="h-6 w-6" />
                </div>
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ title }}
                    </dt>
                    <dd>
                        <div class="text-lg font-medium text-gray-900">
                            {{ formattedValue }}
                        </div>
                    </dd>
                </dl>
            </div>
            <div v-if="href" class="flex-shrink-0">
                <Link :href="href" class="text-gray-400 hover:text-gray-500">
                    <ChevronRight class="h-5 w-5" />
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import {
    Package,
    FolderOpen,
    Image,
    CheckCircle,
    Users,
    ShoppingCart,
    TrendingUp,
    DollarSign,
    ChevronRight
} from 'lucide-vue-next';

interface Props {
    title: string;
    value: number | string;
    icon: string;
    color: 'blue' | 'green' | 'purple' | 'emerald' | 'red' | 'yellow' | 'indigo' | 'pink';
    href?: string;
}

const props = defineProps<Props>();

const iconComponents = {
    Package,
    FolderOpen,
    Image,
    CheckCircle,
    Users,
    ShoppingCart,
    TrendingUp,
    DollarSign,
};

const iconComponent = computed(() => {
    return iconComponents[props.icon as keyof typeof iconComponents] || Package;
});

const formattedValue = computed(() => {
    if (typeof props.value === 'number') {
        return props.value.toLocaleString();
    }
    return props.value;
});

const cardClasses = computed(() => [
    'relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden',
    props.href ? 'hover:shadow-md transition-shadow cursor-pointer' : ''
]);

const colorClasses = {
    blue: 'bg-blue-500',
    green: 'bg-green-500',
    purple: 'bg-purple-500',
    emerald: 'bg-emerald-500',
    red: 'bg-red-500',
    yellow: 'bg-yellow-500',
    indigo: 'bg-indigo-500',
    pink: 'bg-pink-500',
};

const iconClasses = computed(() => [
    'flex items-center justify-center h-10 w-10 rounded-md text-white',
    colorClasses[props.color]
]);
</script>