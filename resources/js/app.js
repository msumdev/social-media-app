import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from "pinia";
import { UserStore } from '@/Stores/userStore';
import { DarkModeStore } from "@/Stores/DarkModeStore.js";
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import UnauthenticatedLayout from "@/Layouts/UnauthenticatedLayout.vue";
import PortalVue from 'portal-vue';

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        let page = pages[`./Pages/${name}.vue`];

        if (name.startsWith('Auth') || name.startsWith('Register')) {
            page.default.layout = UnauthenticatedLayout;
        } else {
            page.default.layout = DefaultLayout;
        }

        return page;
    },
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();
        pinia.use(piniaPluginPersistedstate)

        const app = createApp({ render: () => h(App, props) })
            .use(PortalVue)
            .use(plugin)
            .use(pinia);

        const darkModeStore = DarkModeStore();
        darkModeStore.applyDarkMode();

        const userStore = UserStore();

        if (props.initialPage.props.requires_auth) {
            (async () => {
                await userStore.getCurrentUser();
            })();
        }

        app.mount(el);
    }
});
