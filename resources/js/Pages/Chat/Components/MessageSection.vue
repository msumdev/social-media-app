<template>
    <div class="col-12 col-lg-10 col-md-12 col-sm-12 p-4 overflow-auto flex-grow-1 d-flex justify-content-center align-items-center" v-if="!currentRoom">
        <div class="spinner-grow text-primary" style="width: 5rem; height: 5rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div id="chat-section" class="col-12 col-lg-10 col-md-12 col-sm-12 chat-messages p-4 overflow-auto flex-grow-1" v-else>
        <div class="row mb-4 d-flex justify-content-center">
            <div class="col-2">
                <div ref="messageLoadingDiv" class="loading-div">
                    <div class="spinner-border text-primary" role="status" v-if="loading">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-for="(message, index) in currentRoom.messages" v-if="currentRoom.messages?.length > 0">
            <div class="d-flex mb-3 justify-content-end chat-container" v-if="message.author.id === user.id">
                <small class="me-3 text-muted align-self-center">
                    {{ message.created_at }}
                </small>
                <div class="dropdown d-flex align-items-center me-3">
                    <button id="moreDropdown" type="button" class="btn btn-link border-0 d-flex align-items-center justify-content-center rounded-circle icon-small-circle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical fs-3"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreDropdown">
                        <li><button class="dropdown-item fs-4" v-if="message.author.id === user.id" @click="deleteMessage(message.id)">Delete</button></li>
                        <li><button class="dropdown-item fs-4" v-if="message.author.id != user.id" @click="reportMessage(message.id)">Report</button></li>
                    </ul>
                </div>
                <div class="bg-primary text-white p-3 rounded-3 shadow-sm" style="max-width: 900px;">
                    <p class="mb-0">{{ message.content }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center mb-3 chat-container" v-else>
                <!-- Profile Picture -->
                <Link :href="'/u/' + message.author.username" class="profile-img profile-img_100">
                    <img :src="message.author.profile_picture" :title="message.author.name" alt="Profile Picture" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                </Link>

                <div class="p-3 rounded-3 shadow-sm chat-message" style="max-width: 900px;">
                    <p class="mb-0">{{ message.content }}</p>
                </div>

                <div class="dropdown d-flex align-items-center ms-3">
                    <button id="moreDropdown" type="button" class="btn btn-link border-0 d-flex align-items-center justify-content-center rounded-circle icon-small-circle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical fs-3"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreDropdown">
                        <li><button class="dropdown-item fs-4" v-if="message.author.id === user.id" @click="deleteMessage(message.id)">Delete</button></li>
                        <li><button class="dropdown-item fs-4" v-if="message.author.id != user.id" @click="reportMessage(message.id)">Report</button></li>
                    </ul>
                </div>

                <small class="ms-3 text-muted align-self-center">
                    {{ message.created_at }}
                </small>
            </div>
        </div>
        <div v-else>
            <p class="text-center">No messages to display.</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {useToast} from "vue-toastification";
import { Link } from "@inertiajs/vue3";

export default {
    components: {
        Link,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
        currentRoom: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        this.toast = useToast();

        this.observer = new IntersectionObserver(this.handleIntersection, {
            root: null,
            rootMargin: '0px',
            threshold: 1
        });

        const loadingDiv = this.$refs.messageLoadingDiv;

        if (loadingDiv) {
            this.observer.observe(loadingDiv);
        }
    },
    data() {
        return {
            toast: null,
            observer: null,
            loading: false,
        }
    },
    methods: {
        deleteMessage(id) {
            axios.delete(`/chat/room/${this.currentRoom.id}/message/${id}`)
                .then(() => {
                    this.currentRoom.messages = this.currentRoom.messages.filter((message) => message.id !== id)

                    this.toast.success("Message deleted");
                })
                .catch((error) => {
                    for (const key in error.response.data.errors) {
                        this.toast.error(error.response.data.errors[key][0]);
                    }
                })
        },
        reportMessage(id) {
            this.$emit('report-modal-clicked', id);
        },
        loadMoreMessages() {
            this.$emit('load-more-messages');
        },
        async handleIntersection(entries, observer) {
            if (entries[0].isIntersecting) {
                if (this.currentRoom.moreMessagesLink) {
                    this.loading = true;

                    setTimeout(() => {
                        this.loadMoreMessages();
                        this.loading = false;
                    }, 1000);
                }
            }
        },
    }
}
</script>

<style scoped>
.ql-editor.ql-blank::before {
    font-size: 1em;
}
</style>
