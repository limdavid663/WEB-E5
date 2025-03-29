// Index.vue
<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useConfirm } from 'primevue/useconfirm';

// Props
interface Category {
    id: number;
    name: string;
    products_count: number;  // Count of associated products
}

interface Props {
    categories: Category[];
}

const props = defineProps<Props>();

// Setup
const confirm = useConfirm();
const confirmDelete = (event: Event, category: Category) => {
    confirm.require({
        target: event.currentTarget as HTMLElement,
        message: `Are you sure you want to delete ${category.name}?`,
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: () => {
            router.delete(route('categories.destroy', category.id));
        }
    });
};
</script>

<template>
    <Card class="card">
        <template #content>
            <Head title="Categories" />
            <ConfirmDialog />
            <div class="flex justify-end mb-4">
                <div>
                    <Link :href="route('categories.create')">
                        <Button label="Add Category" icon="pi pi-plus" class="p-button-success" />
                    </Link>
                </div>
            </div>
            <DataTable
                :value="props.categories"
                :rowHover="true"
                scrollable
            >
                <Column field="name" header="Name" style="min-width: 200px"></Column>
                <Column field="products_count" header="Products" style="min-width: 150px">
                    <template #body="slotProps">
                        <Badge :value="slotProps.data.products_count"
                               severity="info" />
                    </template>
                </Column>
                <Column header="Actions" style="min-width: 150px">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <Link :href="route('categories.edit', slotProps.data.id)">
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
