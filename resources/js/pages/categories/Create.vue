// Create.vue
<script setup lang="ts">
import Form from '@/pages/categories/components/Form.vue';

// Define props to receive products from the controller
interface Props {
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
        <!-- Category Form Component -->
        <Form
            mode="create"
            :all-products="props.allProducts"
        />
    </div>
</template>
