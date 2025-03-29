// Index.vue
<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useConfirm } from 'primevue/useconfirm';

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

// Setup
const confirm = useConfirm();

const confirmDelete = (event: Event, product: Product) => {
    confirm.require({
        target: event.currentTarget as HTMLElement,
        message: `Are you sure you want to delete ${product.name}?`,
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(route('products.destroy', product.id));
        }
    });
};
</script>

<template>

    <Card class="card">
        <template #content>
            <Head title="Products" />
            <ConfirmDialog />

            <div class="flex justify-end mb-4">
                <div>
                    <Link :href="route('products.create')">
                        <Button label="Add Product" icon="pi pi-plus" class="p-button-success" />
                    </Link>
                </div>
            </div>

            <DataTable
                :value="props.products"
                :rowHover="true"
                scrollable
            >
                <Column field="image" header="Image" style="min-width: 100px">
                    <template #body="slotProps">
                        <Image
                            :src="slotProps.data.image || '/images/placeholder.jpeg'"
                            :alt="slotProps.data.name"
                            class="shadow-2 border-round"
                            style="width: 64px; height: 64px; object-fit: cover;"
                            preview
                        />
                    </template>
                </Column>

                <Column field="name" header="Name" style="min-width: 200px"></Column>

                <Column field="description" header="Description" style="min-width: 300px">
                    <template #body="slotProps">
                        <div class="line-clamp-2">{{ slotProps.data.description }}</div>
                    </template>
                </Column>

                <Column field="price" header="Price" style="min-width: 150px">
                    <template #body="slotProps">
                        {{ slotProps.data.price }}
                    </template>
                </Column>

                <Column field="quantity" header="Quantity" style="min-width: 150px">
                    <template #body="slotProps">
                        <Badge :value="slotProps.data.quantity"
                               :severity="slotProps.data.quantity > 0 ? 'success' : 'danger'" />
                    </template>
                </Column>

                <Column header="Actions" style="min-width: 150px">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <Link :href="route('products.edit', slotProps.data.id)">
                                <Button icon="pi pi-pencil"
                                        class="p-button-rounded p-button-success p-button-text" />
                            </Link>

                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-danger p-button-text"
                                @click="confirmDelete($event, slotProps.data)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>


        </template>
    </Card>

</template>
