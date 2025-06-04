<template>
    <nav
        class="hidden md:block bg-white dark:bg-gray-800 dark:border-gray-700 transition-all duration-300"
        :class="[siteStore.isMessengerSideBarExpanded ? 'w-64 overflow-auto scrollbar-thumb-gray-200 scrollbar-track-gray-300 scrollbar-thin' : 'w-16 overflow-auto  scrollbar-none']"
    >
        <!-- Header Row -->
        <div class="flex items-center justify-between pe-4 ps-4 pt-4 mb-1">
            <div v-if="siteStore.isMessengerSideBarExpanded" class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                    Messages
                </span>
            </div>

            <div class="flex items-center gap-2">
                <button @click="siteStore.toggleMessengerSideBar()" class="text-gray-500 hover:text-gray-800 dark:hover:text-white transition-colors text-2xl leading-none cursor-pointer flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform" :class="siteStore.isMessengerSideBarExpanded ? 'rotate-270' : 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <button v-if="siteStore.isMessengerSideBarExpanded" class="text-gray-500 hover:text-gray-800 dark:hover:text-white transition-colors cursor-pointer flex items-center justify-center w-6 h-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <div
            v-for="friend in friendStore.friends.data"
            :key="friend.id"
            :class="[
                'group p-3 cursor-pointer border-b border-gray-100 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all',
                siteStore.isMessengerSideBarExpanded ? 'flex items-center gap-3' : 'flex flex-col items-center justify-center'
            ]"
        >
            <div class="relative">
                <img
                    :src="friend.profile_picture"
                    alt="Profile Picture"
                    class="w-9 h-9 object-cover rounded-full"
                />
                <span class="absolute bottom-0 right-0 block w-2.5 h-2.5 bg-gray-400 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
            </div>

            <div v-if="siteStore.isMessengerSideBarExpanded" class="flex flex-col">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ friend.first_name }} {{ friend.last_name }}
                </p>
                <span class="text-xs text-gray-500 dark:text-gray-400">Some last message here...</span>
            </div>
        </div>
    </nav>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import { SiteStore } from "@/Stores/siteStore.js";
import { FriendStore } from '@/Stores/friendStore';

export default {
    components: {
        Link,
    },
    data() {
        return {
            siteStore: SiteStore(),
            friendStore: FriendStore(),
        }
    },
    mounted() {
        this.friendStore.getFriends();
    },
}
</script>
