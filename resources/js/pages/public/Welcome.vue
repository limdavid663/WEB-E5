<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

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
}

const props = defineProps<Props>();

// Computed property to organize categories for the menu
const categoryMenuItems = computed(() => {
    return props.categories.map(category => ({
        label: category.name,
        icon: 'pi pi-tag',
        to: {
            path: '/',
            query: { category: category.id }
        }
    }));
});

// Add "All Products" item at the beginning
const menuItems = computed(() => {
    return [
        {
            label: 'All Products',
            icon: 'pi pi-home',
            to: '/'
        },
        ...categoryMenuItems.value
    ];
});

// Active menu item based on current category
const activeIndex = computed(() => {
    if (!props.currentCategory) return 0;

    const index = props.categories.findIndex(
        category => category.id === props.currentCategory?.id
    );

    return index >= 0 ? index + 1 : 0;
});

// Helper function to display category names for a product
const getCategoryNames = (product: Product) => {
    return product.categories.map(cat => cat.name).join(', ');
};
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-between gap-4">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <Button
                            label="All Products"
                            :class="{ 'p-button-outlined': currentCategory !== null }"
                            @click="$inertia.get('/')"
                        />
                        <Button
                            v-for="category in categories"
                            :key="category.id"
                            :label="category.name"
                            :class="{ 'p-button-outlined': currentCategory?.id !== category.id }"
                            @click="$inertia.get('/', { category: category.id })"
                        />
                    </div>
                </div>

                <Link
                    v-if="$page.props.auth.user && $page.props.auth.user.is_super_admin"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
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
