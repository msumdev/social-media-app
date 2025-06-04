<template>
    <div class="relative inline-block text-left">
        <div>
            <div class="relative">
                <img class="w-10 h-10 rounded-full shadow-lg ring-2 ring-gray-300 dark:ring-gray-500 cursor-pointer" src="https://i.pravatar.cc/80" alt="" @click="isOpen = !isOpen">
                <span class="top-0 left-7 absolute  w-3.5 h-3.5 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></span>
            </div>
<!--            <button @click="isOpen = !isOpen" type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 shadow-xs ring-gray-300 ring-inset hover:bg-gray-50 cursor-pointer" id="menu-button" aria-expanded="true" aria-haspopup="true">-->
<!--                Options-->
<!--                <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">-->
<!--                    <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />-->
<!--                </svg>-->
<!--            </button>-->
        </div>

        <div v-if="isOpen" class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white ring-1 shadow-lg ring-black/5 focus:outline-hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
            <div class="py-1" role="none">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Account settings</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-1">Support</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-2">License</a>
                <Link href="/logout" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-red-900 hover:text-white dark:hover:bg-red-950" role="menuitem" tabindex="-1" id="menu-item-3">Sign out</Link>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import { DarkModeStore } from '@/Stores/DarkModeStore';

export default {
    name: "Dropdown",
    components: {
        Link,
    },
    data() {
        return {
            isOpen: false,
        }
    },
}
</script>
