import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
// Import PrimeVue
import PrimeVue from 'primevue/config';

import 'primeicons/primeicons.css';
import Aura from '@primeuix/themes/aura';

import { ConfirmationService, ToastService } from 'primevue';
import AppLayout from '@/layouts/AppLayout.vue';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;

        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const page: any = await resolvePageComponent(`./pages/${name}.vue`,
            import.meta.glob('./pages/**/*.vue'));

        page.default.layout = name.startsWith('public/') ? page.default.layout : AppLayout;

        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        darkModeSelector: '.dark'
                    }
                }
            })
            .use(ConfirmationService)
            .use(ToastService)
            .mount(el);
    },
    progress: {
        color: '#4B5563'
    }
});

// This will set light / dark mode on page load...
initializeTheme();
