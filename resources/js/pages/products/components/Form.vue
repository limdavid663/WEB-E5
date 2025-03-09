<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { FileUploadUploaderEvent } from 'primevue';

interface ProductFormProps {
    initialData?: {
        id?: number;
        name?: string;
        description?: string;
        price?: number | null;
        quantity?: number | null;
        image?: string | null;
    };
    mode: 'create' | 'update';
}

const props = withDefaults(defineProps<ProductFormProps>(), {
    initialData: () => ({
        name: '',
        description: '',
        price: null,
        quantity: null,
        image: null
    })
});

// Form setup with Inertia
const form = useForm({
    name: props.initialData.name || '',
    description: props.initialData.description || '',
    price: props.initialData.price || null,
    quantity: props.initialData.quantity || null,
    image: props.initialData.image,
    _method: props.mode === 'update' ? 'PUT' : undefined
});

// Preview for uploaded image
const imagePreview = ref<string | null>(props.initialData.image || null);

// computed
const submitUrl = computed(() => {
    return props.mode === 'create' ? route('products.store') : route('products.update', props.initialData.id);
});

const submitButtonLabel = computed(() => {
    return props.mode === 'create' ? 'Create' : 'Update';
});

// Load existing image if available
onMounted(() => {
    if (props.initialData.image) {
        imagePreview.value = props.initialData.image;
    }
});

function onFileUpload(event: FileUploadUploaderEvent) {
    // Safely handle both single File or File[] scenarios
    const files = Array.isArray(event.files) ? event.files : [event.files];
    const file = files[0]; // Now safely get the first file

    if (file) {
        // Set the single file to form data
        form.image = file;

        // Create preview for the file
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
}

// Function to remove image
function removeImage() {
    // Clear preview
    imagePreview.value = null;
    // Clear form data
    form.image = null;
}

/**
 * Submit the form to create/update product
 */
function submitForm() {
    console.log('form', form);
    form.post(submitUrl.value, {
        preserveScroll: true
    });
}
</script>

<template>
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ submitButtonLabel }}</h1>
    </div>
    <!-- Product Form -->
    <form
        id="product-form"
        @submit.prevent="submitForm"
    >
        <!-- Product Information Panel -->
        <Panel header="Product Information">
            <div class="grid grid-cols-12 gap-4">
                <!-- Product Name -->
                <div class="mb-4 col-span-12">
                    <IftaLabel>
                        <InputText
                            id="name"
                            v-model="form.name"
                            :class="{'border-red-500': form.errors.name}"
                            class="w-full"
                        />
                        <label for="name">Product Name*</label>
                    </IftaLabel>
                    <small v-if="form.errors.name" class="text-red-500">
                        {{ form.errors.name }}
                    </small>
                </div>

                <!-- Price -->
                <div class="mb-4 col-span-12 md:col-span-6">
                    <IftaLabel>
                        <InputNumber
                            id="price"
                            v-model="form.price"
                            :class="{'border-red-500': form.errors.price}"
                            mode="currency"
                            currency="USD"
                            locale="en-US"
                            :min="0"
                            class="w-full"
                        />
                        <label for="price">Price*</label>
                    </IftaLabel>
                    <small v-if="form.errors.price" class="text-red-500">
                        {{ form.errors.price }}
                    </small>
                </div>

                <!-- Quantity -->
                <div class="mb-4 col-span-12 md:col-span-6">
                    <IftaLabel>
                        <InputNumber
                            id="quantity"
                            v-model="form.quantity"
                            :class="{'border-red-500': form.errors.quantity}"
                            :min="0"
                            showButtons
                            class="w-full"
                        />
                        <label for="quantity">Quantity*</label>
                    </IftaLabel>
                    <small v-if="form.errors.quantity" class="text-red-500">
                        {{ form.errors.quantity }}
                    </small>
                </div>


                <!-- Description -->
                <div class="mb-4 col-span-12">
                    <IftaLabel>
            <Textarea
                id="description"
                v-model="form.description"
                :class="{'border-red-500': form.errors.description}"
                class="w-full"
                rows="4"
                autoResize
            />
                        <label for="description">Description</label>
                    </IftaLabel>
                    <small v-if="form.errors.description" class="text-red-500">
                        {{ form.errors.description }}
                    </small>
                </div>
            </div>
        </Panel>

        <!-- Product Image Panel -->
        <Panel header="Product Image" class="mt-3">
            <div class="mb-4">
                <!-- Single image preview -->
                <div v-if="imagePreview" class="mb-3 flex justify-center">
                    <div class="relative">
                        <div class="p-8 rounded-border flex flex-col border border-surface items-center gap-4">
                            <Image
                                :src="imagePreview"
                                width="150"
                                preview
                                imageClass="object-cover rounded shadow"
                            />
                            <Button
                                icon="pi pi-times"
                                @click="removeImage"
                                outlined
                                rounded
                                severity="danger"
                            />
                        </div>
                    </div>
                </div>
                <!-- File upload for single image -->
                <FileUpload
                    id="product-image"
                    mode="basic"
                    accept="image/*"
                    chooseLabel="Select Image"
                    class="w-full"
                    :customUpload="true"
                    :auto="true"
                    :multiple="false"
                    :class="{'border-red-500': form.errors.image}"
                    @uploader="onFileUpload"
                />
                <small v-if="form.errors.image" class="text-red-500 block mt-2">
                    {{ form.errors.image }}
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
                    @click="router.visit(route('products.index'))"
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
