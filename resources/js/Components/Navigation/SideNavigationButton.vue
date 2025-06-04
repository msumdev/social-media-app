<template>
    <div class="px-2 py-2">
        <Link
            :href="url"
            role="button"
            class="flex items-center w-full p-2 leading-tight transition-all rounded-md outline-none text-start relative text-white hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-700 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:active:bg-gray-700 group cursor-pointer"
            :class="[
              isActive ? 'bg-blue-700 dark:bg-gray-700' : '',
              !siteStore.isSideBarExpanded ? 'justify-center' : ''
            ]"
        >
            <div class="grid place-items-center" :class="[siteStore.isSideBarExpanded ? 'mr-4' : '']">
                <slot name="icon" />
            </div>
            <span class="text-sm" v-if="siteStore.isSideBarExpanded">{{ label }}</span>
        </Link>
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3'
import { SiteStore } from "@/Stores/siteStore.js";

export default {
    name: 'SidebarButton',
    components: {
        Link,
    },
    props: {
        label: {
            type: String,
            required: true,
        },
        url: {
            type: String,
            required: true,
        }
    },
    computed: {
        isActive() {
            return location.pathname === this.url;
        }
    },
    data() {
        return {
            siteStore: SiteStore(),
        }
    }
};
</script>
