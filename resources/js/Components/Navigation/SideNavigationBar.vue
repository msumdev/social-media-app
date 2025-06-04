<template>
    <nav
        class="hidden md:block bg-blue-600 transition-all duration-300 drop-shadow-md overflow-hidden dark:bg-gray-800"
        :class="[siteStore.isSideBarExpanded ? 'w-[200px]' : 'w-16 pt-2']"
    >
        <div class="flex items-center space-x-4 rounded-lg p-4 text-white" v-if="siteStore.isSideBarExpanded">
        <img :src="userStore.user.profile_picture" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover shadow-md" />
            <div class="flex flex-col">
                <p class="font-semibold text-sm break-all max-w-full">John Doe</p>
                <p class="text-xs break-all max-w-full">john@example.com</p>
            </div>
        </div>

        <side-navigation-button label="Home" url="/">
            <template #icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </template>
        </side-navigation-button>

        <side-navigation-button label="Chat" url="/chat">
            <template #icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
            </template>
        </side-navigation-button>

        <side-navigation-button label="Friends" url="/friends">
            <template #icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </template>
        </side-navigation-button>

        <side-navigation-button label="Gallery" url="/gallery">
            <template #icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            </template>
        </side-navigation-button>

        <side-navigation-button label="Events" url="/events">
            <template #icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
            </template>
        </side-navigation-button>

        <div class="absolute bottom-0 w-full flex flex-col space-y-2 px-2 py-2 border-t border-white/10 dark:border-gray-700 bg-blue-700 dark:bg-gray-900">
            <button @click="siteStore.toggleSideBarExpanded()" class="flex items-center space-x-3 text-white hover:bg-blue-800 dark:hover:bg-gray-700 p-2 rounded transition-all cursor-pointer" :class="!siteStore.isSideBarExpanded ? 'justify-center' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" :class="['size-5 transition-transform', siteStore.isSideBarExpanded ? 'rotate-90' : 'rotate-270']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                <span v-if="siteStore.isSideBarExpanded" class="text-sm">Collapse</span>
            </button>

            <button class="flex items-center space-x-3 text-white hover:bg-blue-800 dark:hover:bg-gray-700 p-2 rounded transition-all cursor-pointer" :class="!siteStore.isSideBarExpanded ? 'justify-center' : ''">
                <i class="fa-solid fa-gear"></i>
                <span v-if="siteStore.isSideBarExpanded" class="text-sm">Settings</span>
            </button>

            <Link href="/logout" class="flex items-center space-x-3 text-white hover:bg-red-900 dark:hover:bg-red-950 p-2 rounded transition-all cursor-pointer" :class="!siteStore.isSideBarExpanded ? 'justify-center' : ''">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span v-if="siteStore.isSideBarExpanded" class="text-sm">Logout</span>
            </Link>
        </div>

    </nav>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import SideNavigationButton from "@/Components/Navigation/SideNavigationButton.vue";
import { SiteStore } from "@/Stores/siteStore.js";
import { UserStore } from '@/Stores/userStore';

export default {
    components: {
        SideNavigationButton,
        Link,
    },
    data() {
        return {
            isExpanded: true,
            siteStore: SiteStore(),
            userStore: UserStore(),
        }
    }
}
</script>
