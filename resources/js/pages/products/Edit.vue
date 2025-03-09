<script setup lang="ts">
import Form from '@/pages/products/components/Form.vue';

// Define props with TypeScript interface
interface Props {
    product: {
        id: number;
        name: string;
        description: string;
        price: number;
        quantity: number;
        image?: string;
    };
}

const props = defineProps<Props>();
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <Form
            :initial-data="props.product"
            mode="update"
        />
    </div>
</template>
