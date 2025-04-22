<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// Props
interface Category {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    quantity: number;
    image: string;
}

interface Props {
    products: Product[];
    categories: Category[];
    currentCategory: Category | null;
    search: string;
}

const props = defineProps<Props>();
// Create a ref for the search input
const searchQuery = ref(props.search);

let searchTimeout: any = null;

watch(searchQuery, (newValue) => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        // Get current URL parameters
        const params = new URLSearchParams(window.location.search);

        // Update or remove the search parameter
        if (newValue) {
            params.set('search', newValue);
        } else {
            params.delete('search');
        }

        // Preserve the category parameter if it exists
        if (props.currentCategory) {
            params.set('category', props.currentCategory.id);
        }

        // Navigate to the updated URL
        router.get('/', Object.fromEntries(params), {
            preserveState: true,
            replace: true,
            preserveScroll: true
        });
    }, 300); // 300ms debounce
});
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="mb-6 w-full">
            <nav class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="w-full md:w-64 lg:w-80">
            <span class="p-input-icon-left w-full">
                <i class="pi pi-search" />
                <InputText
                    v-model="searchQuery"
                    placeholder="Search products..."
                    class="w-full"
                />
            </span>
                </div>

                <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                    <Button
                        label="All Products"
                        size="small"
                        :class="{ 'p-button-outlined': currentCategory !== null }"
                        @click="$inertia.get('/')"
                    />
                    <Button
                        v-for="category in categories"
                        :key="category.id"
                        :label="category.name"
                        size="small"
                        :class="{ 'p-button-outlined': currentCategory?.id !== category.id }"
                        @click="$inertia.get('/', { category: category.id })"
                    />
                </div>

                <!-- Admin link - properly positioned on all screen sizes -->
                <Link
                    v-if="$page.props.auth.user && $page.props.auth.user.is_super_admin"
                    :href="route('products.index')"
                    class="mt-3 md:mt-0 inline-block rounded-sm border border-[#19140035] px-4 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Admin
                </Link>
            </nav>
        </header>

        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main
                class="mx-auto flex h-full w-full max-w-7xl flex-1 flex-col gap-4 rounded-xl">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <Card v-for="(product, index) in props.products" :key="index" class="overflow-hidden">
                        <template #header>
                            <img
                                :src="product.image"
                                :alt="product.name"
                            />
                        </template>
                        <template #title>{{ product.name }}</template>
                        <template #subtitle>{{ product.description }}</template>
                        <template #content>
                            <p class="m-0">
                                ${{ product.price }}
                            </p>
                        </template>
                    </Card>
                </div>
            </main>
        </div>

        <div class="h-14.5 hidden lg:block"></div>
    </div>
</template>
