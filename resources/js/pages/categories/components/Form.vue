<script setup lang="ts">
import { computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

interface CategoryFormProps {
    initialData?: {
        id?: number;
        name?: string;
        products?: Array<{
            id: number;
            name: string;
        }>;
    };
    mode: 'create' | 'update';
    allProducts: Array<{
        id: number;
        name: string;
        price?: number;
        image?: string | null;
    }>;
}

const props = withDefaults(defineProps<CategoryFormProps>(), {
    initialData: () => ({
        name: '',
        products: []
    }),
    allProducts: () => []
});

// Form setup with Inertia
const form = useForm({
    name: props.initialData.name || '',
    product_ids: props.initialData.products?.map(p => p.id) || [],
    _method: props.mode === 'update' ? 'PUT' : undefined
});

// computed
const submitUrl = computed(() => {
    return props.mode === 'create' ? route('categories.store') : route('categories.update', props.initialData.id);
});

const submitButtonLabel = computed(() => {
    return props.mode === 'create' ? 'Create' : 'Update';
});

/**
 * Submit the form to create/update category
 */
function submitForm() {
    form.post(submitUrl.value, {
        preserveScroll: true
    });
}
</script>

<template>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ submitButtonLabel }} Category</h1>
    </div>

    <!-- Category Form -->
    <form
        id="category-form"
        @submit.prevent="submitForm"
    >
        <!-- Category Information Panel -->
        <Panel header="Category Information">
            <div class="grid grid-cols-12 gap-4">
                <!-- Category Name -->
                <div class="mb-4 col-span-12">
                    <IftaLabel>
                        <InputText
                            id="name"
                            v-model="form.name"
                            :class="{'border-red-500': form.errors.name}"
                            class="w-full"
                        />
                        <label for="name">Category Name*</label>
                    </IftaLabel>
                    <small v-if="form.errors.name" class="text-red-500">
                        {{ form.errors.name }}
                    </small>
                </div>
            </div>
        </Panel>

        <!-- Products Panel -->
        <Panel header="Associated Products" class="mt-3">
            <div class="mb-4 col-span-12">
                <IftaLabel>
                    <MultiSelect
                        id="products"
                        v-model="form.product_ids"
                        :options="props.allProducts || []"
                        optionLabel="name"
                        optionValue="id"
                        :filter="true"
                        :class="{'border-red-500': form.errors.product_ids}"
                        class="w-full"
                        display="chip"
                    >
                        <template #option="slotProps">
                            <div class="flex items-center gap-2">
                                <img
                                    v-if="slotProps.option && slotProps.option.image"
                                    :src="slotProps.option.image"
                                    style="width: 32px; height: 32px; object-fit: cover;"
                                    class="rounded"
                                    alt="Product Image"
                                />
                                <div>
                                    <div>{{ slotProps.option?.name || 'Unknown Product' }}</div>
                                    <small v-if="slotProps.option && slotProps.option.price !== undefined">
                                        ${{ Number(slotProps.option.price).toFixed(2) }}
                                    </small>
                                </div>
                            </div>
                        </template>
                    </MultiSelect>
                </IftaLabel>
                <small v-if="form.errors.product_ids" class="text-red-500">
                    {{ form.errors.product_ids }}
                </small>
            </div>
        </Panel>

        <!-- Form actions slot -->
        <div class="flex justify-end mt-4 space-x-2">
            <slot name="actions">
                <Button
                    type="button"
                    label="Cancel"
                    icon="pi pi-times"
                    class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-100"
                    @click="router.visit(route('categories.index'))"
                />
                <Button
                    type="submit"
                    :label="submitButtonLabel"
                    icon="pi pi-check"
                    :loading="form.processing"
                    :disabled="form.processing"
                />
            </slot>
        </div>
    </form>
</template>
