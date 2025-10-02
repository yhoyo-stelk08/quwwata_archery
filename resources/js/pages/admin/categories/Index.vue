<template>
    <AdminLayout>
        <Head title="Categories" />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                    <p class="text-gray-600 mt-2">Manage your product categories</p>
                </div>
                <Link
                    :href="route('admin.categories.create')"
                    class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors shadow-lg hover:shadow-xl"
                >
                    <Plus class="h-5 w-5 mr-2" />
                    Add Category
                </Link>
            </div>

            <!-- Categories Table -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Parent
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Products
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sort Order
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="category in categories"
                                :key="category.id"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img
                                                v-if="category.image"
                                                :src="`/storage/${category.image}`"
                                                :alt="category.name"
                                                class="h-12 w-12 rounded-lg object-cover"
                                            />
                                            <div
                                                v-else
                                                class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center"
                                            >
                                                <FolderOpen class="h-6 w-6 text-gray-400" />
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ category.name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ category.slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span v-if="category.parent" class="text-gray-600">
                                        {{ category.parent.name }}
                                    </span>
                                    <span v-else class="text-gray-400 italic">
                                        Root Category
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ category.products_count }} products
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            category.is_active
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800'
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'w-1.5 h-1.5 mr-1.5 rounded-full',
                                                category.is_active ? 'bg-green-400' : 'bg-red-400'
                                            ]"
                                        ></span>
                                        {{ category.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ category.sort_order || 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <Link
                                            :href="route('admin.categories.show', category.id)"
                                            class="text-blue-600 hover:text-blue-900 p-1"
                                            title="View"
                                        >
                                            <Eye class="h-4 w-4" />
                                        </Link>
                                        <Link
                                            :href="route('admin.categories.edit', category.id)"
                                            class="text-amber-600 hover:text-amber-900 p-1"
                                            title="Edit"
                                        >
                                            <Edit class="h-4 w-4" />
                                        </Link>
                                        <button
                                            @click="deleteCategory(category)"
                                            :class="[
                                                'p-1 transition-colors',
                                                category.products_count > 0
                                                    ? 'text-gray-300 cursor-not-allowed'
                                                    : 'text-red-600 hover:text-red-900 cursor-pointer'
                                            ]"
                                            :title="category.products_count > 0 ? 'Cannot delete category with products' : 'Delete'"
                                            :disabled="category.products_count > 0"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div v-if="categories.length === 0" class="text-center py-12">
                        <FolderOpen class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                        <div class="mt-6">
                            <Link
                                :href="route('admin.categories.create')"
                                class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors"
                            >
                                <Plus class="h-5 w-5 mr-2" />
                                Add Category
                            </Link>
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
                                <AlertTriangle class="h-6 w-6 text-red-600" />
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Delete Category
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete "{{ categoryToDelete?.name }}"? 
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
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import {
    Plus,
    FolderOpen,
    Eye,
    Edit,
    Trash2,
    AlertTriangle,
} from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
    slug: string;
    image?: string;
    is_active: boolean;
    sort_order?: number;
    products_count: number;
    parent?: {
        name: string;
    };
}

defineProps<{
    categories: Category[];
}>();

const showDeleteModal = ref(false);
const categoryToDelete = ref<Category | null>(null);

const deleteCategory = (category: Category) => {
    console.log('Delete button clicked for category:', category.name);
    console.log('Products count:', category.products_count);
    
    if (category.products_count > 0) {
        alert('Cannot delete category that has products.');
        return;
    }
    
    categoryToDelete.value = category;
    showDeleteModal.value = true;
    console.log('Modal should be shown now');
};

const confirmDelete = () => {
    console.log('Confirm delete clicked');
    if (categoryToDelete.value) {
        console.log('Deleting category:', categoryToDelete.value.name);
        router.delete(route('admin.categories.destroy', categoryToDelete.value.id), {
            onSuccess: () => {
                console.log('Delete successful');
                showDeleteModal.value = false;
                categoryToDelete.value = null;
            },
            onError: (errors) => {
                console.error('Delete failed:', errors);
            },
        });
    }
};
</script>