<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

// Props
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
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user && $page.props.auth.user.is_super_admin"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
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
                        <template #title>{{product.name}}</template>
                        <template #subtitle>{{product.description}}</template>
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
