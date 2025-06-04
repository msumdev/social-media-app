import { createApp, h } from 'vue'
import Toast from '@/Components/Utilities/Toast.vue'

export default function toast(message = '', type = 'info', duration = 3000) {
    const container = document.createElement('div');
    document.body.appendChild(container);

    const app = createApp({
        render: () => h(Toast, { message, type, duration })
    });

    app.mount(container);

    // Optionally return app if you want further control
    return app;
}
