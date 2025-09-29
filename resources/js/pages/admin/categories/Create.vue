<template>
    <AdminLayout>
        <Head title="Create Category" />

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <Link :href="route('admin.categories.index')" class="hover:text-amber-600">
                        Categories
                    </Link>
                    <ChevronRight class="h-4 w-4" />
                    <span>Create</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Create Category</h1>
                <p class="text-gray-600 mt-2">Add a new product category</p>
            </div>

            <!-- Form -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg">
                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <!-- Category Name -->
                    <div>
                        <Label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Name *
                        </Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            name="name"
                            required
                            placeholder="Enter category name"
                            class="w-full"
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <Label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Category
                        </Label>
                        <select
                            id="parent_id"
                            v-model="form.parent_id"
                            name="parent_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        >
                            <option value="">Select parent category (optional)</option>
                            <option
                                v-for="category in parentCategories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.parent_id" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <Label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            name="description"
                            rows="3"
                            placeholder="Enter category description"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        ></textarea>
                        <InputError :message="form.errors.description" class="mt-2" />
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <Label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                            Category Image
                        </Label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input
                                    id="image"
                                    type="file"
                                    accept="image/*"
                                    @change="handleImageChange"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                />
                                <p class="text-sm text-gray-500 mt-1">
                                    Accepted formats: JPEG, PNG, JPG, WebP. Max size: 2MB
                                </p>
                            </div>
                            <div v-if="imagePreview" class="flex-shrink-0">
                                <img
                                    :src="imagePreview"
                                    alt="Preview"
                                    class="h-16 w-16 rounded-lg object-cover border"
                                />
                            </div>
                        </div>
                        <InputError :message="form.errors.image" class="mt-2" />
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <Label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                            Sort Order
                        </Label>
                        <Input
                            id="sort_order"
                            v-model="form.sort_order"
                            type="number"
                            name="sort_order"
                            placeholder="0"
                            min="0"
                            class="w-full max-w-xs"
                        />
                        <p class="text-sm text-gray-500 mt-1">
                            Lower numbers appear first in listings
                        </p>
                        <InputError :message="form.errors.sort_order" class="mt-2" />
                    </div>

                    <!-- Status -->
                    <div>
                        <Label class="flex items-center space-x-2">
                            <Checkbox
                                v-model="form.is_active"
                                name="is_active"
                            />
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </Label>
                        <p class="text-sm text-gray-500 mt-1">
                            Only active categories will be visible on the website
                        </p>
                        <InputError :message="form.errors.is_active" class="mt-2" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <Link
                            :href="route('admin.categories.index')"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                        >
                            Cancel
                        </Link>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors flex items-center"
                        >
                            <LoaderCircle
                                v-if="form.processing"
                                class="animate-spin -ml-1 mr-2 h-4 w-4"
                            />
                            {{ form.processing ? 'Creating...' : 'Create Category' }}
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { LoaderCircle, ChevronRight } from 'lucide-vue-next';

interface ParentCategory {
    id: number;
    name: string;
}

defineProps<{
    parentCategories: ParentCategory[];
}>();

const form = useForm({
    name: '',
    description: '',
    parent_id: '',
    image: null as File | null,
    sort_order: '',
    is_active: true,
});

const imagePreview = ref<string | null>(null);

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.image = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    } else {
        form.image = null;
        imagePreview.value = null;
    }
};

const submit = () => {
    form.post(route('admin.categories.store'), {
        forceFormData: true,
    });
};
</script>