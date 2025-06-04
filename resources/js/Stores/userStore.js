import { defineStore } from 'pinia'
import axios from "axios";

export const UserStore = defineStore('userStore', {
    state: () => ({
        user: {},
    }),
    actions: {
        setUser(userData) {
            this.user = userData
        },
        async getCurrentUser() {
            await axios.get('/user')
                .then((response) => {
                    this.setUser(response.data);
                });
        }
    }
})
