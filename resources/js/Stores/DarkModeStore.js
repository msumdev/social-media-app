import { defineStore } from 'pinia'

export const DarkModeStore = defineStore('darkMode', {
    state: () => ({
        isEnabled: false
    }),
    actions: {
        toggleDarkMode() {
            this.isEnabled = !this.isEnabled

            this.applyDarkMode()
        },
        applyDarkMode() {
            if (this.isEnabled) {
                document.body.classList.add('dark')
            } else {
                document.body.classList.remove('dark')
            }
        },
    },
    persist: true,
    hydrate(state) {
        state.applyDarkMode();
    }
})
