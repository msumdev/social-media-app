<template>
    <!-- Modal -->
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + '-label'">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-3 shadow-sm">
                <!-- Modal Header -->
                <div class="modal-header rounded-top">
                    <p class="text-white fs-4 fw-bold mb-0">New Chat</p>
                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="modal-body p-0">
                    <PaginatedUserTable
                        :padding-class="'p-3'"
                        :data="friends"
                        :links="friends.meta?.links"
                        :checkable="true"
                        @update-user-table-data="updateUserTableData"
                        @update-selected="updatedSelected"
                    />
                </div>

                <div class="modal-footer justify-content-between">
                    <div class="d-flex align-items-center w-100">
                        <button type="button" class="btn btn-primary ms-auto" :disabled="selected.length === 0" @click="createChat">
                            Create Chat
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from "vue-toastification";

import axios from "axios";
import {v4 as uuidv4} from "uuid";
import PaginatedUserTable from "@/Shared/PaginatedUserTable.vue";

export default {
    components: {
        PaginatedUserTable,
    },
    data() {
        return {
            modalId: uuidv4(),
            toast: null,
            friends: {
                data: [],
                meta: null,
            },
            selected: [],
        };
    },
    mounted() {
        this.toast = useToast();
    },
    methods: {
        toggle() {
            const modal = bootstrap.Modal.getOrCreateInstance("#" + this.modalId);

            this.loadFriends();
            this.selected = [];

            modal.toggle();
        },
        loadFriends() {
            axios.get("/chat/friends")
                .then((response) => {
                    this.friends = response.data;
                })
                .catch((error) => {
                    this.toast.error("Failed to load friends.");
                });
        },
        createChat() {
            if (this.selected.length === 0) {
                this.toast.danger("Please select a friend.");
                return;
            }

            axios.post('/chat/room', {
                members: this.selected,
            })
                .then(() => {
                    this.$emit("chat-created");
                })
                .catch(() => {
                    this.toast.error("Failed to create chat.");
                });
        },
        updateUserTableData(data) {
            this.friends = data;
        },
        updatedSelected(selected) {
            this.selected = selected;
        }
    },
};
</script>

<style scoped>
.modal-body {
    max-height: 500px;
    overflow-y: auto;
}
</style>
