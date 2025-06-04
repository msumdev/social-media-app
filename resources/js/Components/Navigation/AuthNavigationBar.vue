<template>
    <div class="absolute top-4 right-4">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" class="sr-only peer" @click="darkModeStore.toggleDarkMode" :checked="darkModeStore.isEnabled">
            <div class="relative w-14 h-7 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                <i class="fa-solid fa-lightbulb absolute top-[6px] left-[6px] text-yellow-400 text-white text-md pointer-events-none dark:hidden"></i>
                <i class="fas fa-moon absolute top-[6px] right-[6px] text-gray-600 text-md pointer-events-none hidden dark:block"></i>
            </div>
        </label>
    </div>
</template>

<script>
import { DarkModeStore } from '@/Stores/DarkModeStore';

export default {
    components: {
        DarkModeStore,
    },
    data() {
        return {
            darkModeStore: DarkModeStore(),
        }
    },
}
</script>
