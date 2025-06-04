<template>
    <div
        class="relative flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-sm shadow-sm border-l-6 dark:text-gray-400 dark:bg-gray-800 animate-slide-in-right z-1000"
        :class="settings[type].border"
        role="alert"
        @mouseenter="toastStore.pause(id)"
        @mouseleave="toastStore.resume(id)"
    >
        <div
            class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg"
            :class="settings[type].style"
        >
            <i :class="settings[type].icon"></i>
            <span class="sr-only">Info icon</span>
        </div>
        <div class="ms-3 text-sm font-normal">{{ message }}</div>
        <button
            type="button"
            @click="toastStore.remove(id)"
            class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700 cursor-pointer"
            aria-label="Close"
        >
            <span class="sr-only">Close</span>
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div
            class="absolute bottom-0 left-0 h-1 bg-blue-100 dark:bg-gray-500 transition-all duration-100 ease-linear"
            :style="{ width: progress + '%' }"
        ></div>
    </div>
</template>

<script>
import { ToastStore } from "@/Stores/toastStore.js";

export default {
    name: 'Toast',
    props: {
        id: { type: Number, required: true },
        message: { type: String, required: true },
        type: { type: String, default: 'info' },
    },
    computed: {
        toastStore() {
            return ToastStore();
        },
        progress() {
            const toast = this.toastStore.toasts.find(t => t.id === this.id);
            return toast ? toast.progress : 0;
        }
    },
    data() {
        return {
            settings: {
                info: {
                    border: 'border-blue-500 dark:border-blue-800',
                    style: 'text-white bg-blue-500 dark:bg-blue-800 dark:text-blue-200',
                    icon: 'fa-solid fa-info'
                },
                warning: {
                    border: 'border-yellow-500 dark:border-yellow-800',
                    style: 'text-white bg-yellow-500 dark:bg-yellow-800 dark:text-yellow-200',
                    icon: 'fa-solid fa-exclamation'
                },
                danger: {
                    border: 'border-red-500 dark:border-red-800',
                    style: 'text-white bg-red-500 dark:bg-red-800 dark:text-red-200',
                    icon: 'fa-solid fa-xmark'
                },
                success: {
                    border: 'border-green-500 dark:border-green-800',
                    style: 'text-white bg-green-500 dark:bg-green-800 dark:text-blue-200',
                    icon: 'fa-solid fa-check'
                }
            }
        };
    }
};
</script>
