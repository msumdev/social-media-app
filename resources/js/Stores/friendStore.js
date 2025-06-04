import { defineStore } from 'pinia'
import axios from "axios";

export const FriendStore = defineStore('friendStore', {
    state: () => ({
        friends: [],
    }),
    actions: {
        async getFriends() {
            await axios.get('/chat/friends')
                .then((response) => {
                    this.friends = response.data;
                });
        }
    }
})
