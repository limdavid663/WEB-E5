<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { watch } from 'vue';
import { useToast } from 'primevue/usetoast';
import { usePage } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => []
});

// Initialize PrimeVue Toast service
const toast = useToast();
const page = usePage();

// Watch for flash messages and show appropriate toasts
watch(() => page.props.flash, (newFlash: any) => {
    console.log('page.props.flash', page.props.flash);
    if (newFlash.success) {
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: newFlash.success,
            life: 3000
        });
    }

    if (newFlash.error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: newFlash.error,
            life: 3000
        });
    }
}, { immediate: true, deep: true });
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toast />
        <slot />
    </AppLayout>
</template>
