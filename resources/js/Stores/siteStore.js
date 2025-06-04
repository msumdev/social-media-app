import { defineStore } from 'pinia'

export const SiteStore = defineStore('siteStore', {
    state: () => ({
        isSideBarExpanded: false,
        isMessengerSideBarExpanded: true,
    }),
    persist: true,
    actions: {
        toggleSideBarExpanded() {
            this.isSideBarExpanded = !this.isSideBarExpanded;
        },
        toggleMessengerSideBar() {
            this.isMessengerSideBarExpanded = !this.isMessengerSideBarExpanded;
        },
    },
})
