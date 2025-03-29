// Edit.vue
<script setup lang="ts">
import Form from '@/pages/categories/components/Form.vue';
interface Props {
    category: {
        id: number;
        name: string;
        products: Array<{
            id: number;
            name: string;
        }>;
    };
    allProducts: Array<{
        id: number;
        name: string;
        price?: number;
        image?: string | null;
    }>;
}
const props = defineProps<Props>();
</script>
<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <Form
            :initial-data="props.category"
            :all-products="props.allProducts"
            mode="update"
        />
    </div>
</template>
