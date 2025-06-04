<template>
    <header class="bg-white text-white flex items-center justify-between px-4 py-2 border-b-1 dark:border-gray-800 border-gray-200 shadow z-10 dark:bg-gray-900">
        <div class="flex items-center md:space-x-3">
            <div class="text-2xl text-blue-500 font-semibold md:w-64">Wise Media</div>

            <div class="relative group hidden md:block">
                <input type="text" class="peer bg-gray-100 text-black h-10 px-5 pr-10 rounded-full text-sm focus:outline-none transition-all duration-300 ease-in-out w-64 focus:w-96 border border-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:text-white" placeholder="Search..." />
                <button type="submit" class="absolute right-0 top-0 mt-3 mr-4">
                    <svg class="h-4 w-4 fill-current text-gray-600 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path
                            d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center space-x-4 text-blue-500">
<!--            <label class="inline-flex items-center cursor-pointer">-->
<!--                <input type="checkbox" class="sr-only peer" @click="darkModeStore.toggleDarkMode" :checked="darkModeStore.isEnabled">-->
<!--                <div class="relative w-14 h-7 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-700 dark:peer-checked:bg-blue-600">-->
<!--                    <i class="fa-solid fa-lightbulb absolute top-[6px] left-[6px] text-yellow-400 text-white text-md pointer-events-none dark:hidden"></i>-->
<!--                    <i class="fas fa-moon absolute top-[6px] right-[6px] text-gray-600 text-md pointer-events-none hidden dark:block"></i>-->
<!--                </div>-->
<!--            </label>-->

            <div class="py-4">
                <dark-mode-toggle></dark-mode-toggle>
            </div>

            <fwb-dropdown close-inside>
                <template #trigger>
                    <button id="dropdownAvatarNameButton" data-dropdown-toggle="dropdownAvatarName" class="flex items-center text-sm pe-1 font-medium text-gray-900 rounded-full hover:text-blue-600 dark:hover:text-blue-500 md:me-0 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-white cursor-pointer" type="button">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-10 h-10 me-2 rounded-full" :src="userStore.user.profile_picture" alt="user photo">
                        Bonnie Green
                        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                </template>
                <nav class="py-2 text-sm text-gray-700 dark:text-gray-200 w-[9.5rem]">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">My Profile</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                    <Link href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</Link>
                </nav>
            </fwb-dropdown>
        </div>
    </header>

</template>

<script>
import DarkModeToggle from '@/Components/Utilities/DarkModeToggle.vue'
import { UserStore } from '@/Stores/userStore';
import {Link} from "@inertiajs/vue3";
import {FwbDropdown} from "flowbite-vue";

export default {
    components: {
        FwbDropdown,
        Link,
        DarkModeToggle,
    },
    data() {
        return {
            userStore: UserStore(),
            isOpen: false,
        }
    },
}
</script>
