import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createStore } from 'vuex';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import { initializeWebSocket, emitter } from './websocket';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const store = createStore({
    state() {
        return {
            isNightMode: false,
            isLoading: true,
            echo: null,
            posts: [],
        };
    },
    mutations: {
        toggleNightMode(state) {
            state.isNightMode = !state.isNightMode;
        },
        setNightMode(state, value) {
            state.isNightMode = value;
        },
        hidePreloader(state) {
            state.isLoading = false;
        },
    },
    actions: {
        toggleDayNight({ commit }) {
            document.body.classList.toggle('night-mode');

            const isNightMode = document.body.classList.contains('night-mode');
            localStorage.setItem('night-mode', isNightMode ? 'true' : 'false');
            commit('setNightMode', isNightMode);
        },
        loadNightModeFromStorage({ commit }) {
            const nightModeEnabled = localStorage.getItem('night-mode') === 'true';
            if (nightModeEnabled) {
                document.body.classList.add('night-mode');
            }
            commit('setNightMode', nightModeEnabled);
        },
        hidePreloader({ commit }) {
            setTimeout(() => {
                commit('hidePreloader');
            }, 400);
        }
    }
});

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(store) // Use Vuex store
            .use(Toast);

        app.mixin({
            mounted() {
                this.$store.dispatch('loadNightModeFromStorage');

                if (window.location.pathname !== '/login' && !window.websocketInitialized) {
                    const token = this.$page.props.token;
                    initializeWebSocket(token);
                    window.websocketInitialized = true; // Mark as initialized
                }

                window.addEventListener('load', () =>
                    this.$store.dispatch('hidePreloader')
                );
            },
            beforeDestroy() {
                window.removeEventListener('load', () =>
                    this.$store.dispatch('hidePreloader')
                );
            },
            methods: {
                toggleDayNight() {
                    this.$store.dispatch('toggleDayNight');
                },
            },
        });

        app.mount(el);
    },
});
