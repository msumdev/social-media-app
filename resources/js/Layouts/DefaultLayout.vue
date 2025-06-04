<template>
    <div class="h-screen flex flex-col">
        <div class="absolute h-screen top-0 right-0 bg-blue/20 z-20" v-if="toastStore.toasts.length > 0">
            <div class="fixed top-0 right-0 z-20 p-4 max-w-full">
                <div class="w-80 max-w-full">
                    <transition-group tag="div" name="toast" class="space-y-2">
                        <toast v-for="(toast, index) in toastStore.toasts" :key="toast.id" :message="toast.message" :id="toast.id" :type="toast.type"></toast>
                    </transition-group>
                </div>
            </div>
        </div>
        <top-navigation-bar></top-navigation-bar>
        <div class="flex flex-1 overflow-hidden">
            <side-navigation-bar></side-navigation-bar>
            <main class="flex-1 text-white overflow-auto scrollbar-none">
                <slot />
            </main>
            <message-navigation-bar></message-navigation-bar>
        </div>
    </div>
</template>

<script>
import TopNavigationBar from "@/Components/Navigation/TopNavigationBar.vue";
import SideNavigationBar from "@/Components/Navigation/SideNavigationBar.vue";
import MessageNavigationBar from "@/Components/Navigation/MessageNavigationBar.vue";
import { ToastStore } from "@/Stores/toastStore.js";
import Toast from "@/Components/Utilities/Toast.vue";

export default {
    components:{
        TopNavigationBar,
        SideNavigationBar,
        MessageNavigationBar,
        Toast,
    },
    data() {
        return {
            toastStore: ToastStore()
        }
    },
}
</script>
