import { defineStore } from 'pinia'

export const ToastStore = defineStore('toastStore', {
    state: () => ({
        toasts: [],
        counter: 0,
        intervals: {},
    }),
    actions: {
        add(message, type = 'info', duration = 6000) {
            const id = this.counter++;
            const toast = {
                id,
                message,
                type,
                duration,
                startTime: Date.now(),
                elapsed: 0,
                paused: false,
                progress: 100,
            };

            this.toasts.push(toast);

            this.intervals[id] = setInterval(() => {
                const t = this.toasts.find(t => t.id === id);
                if (!t || t.paused) return;

                const now = Date.now();
                t.elapsed = now - t.startTime;
                t.progress = Math.max(0, 100 - (t.elapsed / t.duration) * 100);

                if (t.elapsed >= t.duration) {
                    this.remove(id);
                }
            }, 100);
        },
        remove(id) {
            this.toasts = this.toasts.filter(toast => toast.id !== id);
            clearInterval(this.intervals[id]);
            delete this.intervals[id];
        },
        pause(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast && !toast.paused) {
                toast.paused = true;
                toast.pauseTime = Date.now();
            }
        },
        resume(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast && toast.paused) {
                toast.paused = false;
                const pausedDuration = Date.now() - toast.pauseTime;
                toast.startTime += pausedDuration;
            }
        },
        info(message, duration) {
            this.add(message, 'info', duration);
        },
        success(message, duration) {
            this.add(message, 'success', duration);
        },
        warning(message, duration) {
            this.add(message, 'warning', duration);
        },
        danger(message, duration) {
            this.add(message, 'danger', duration);
        },
    },
});
