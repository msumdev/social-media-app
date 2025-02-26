<template>
    <!-- Modal -->
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + '-label'">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-3 shadow-sm">
                <!-- Modal Header -->
                <div class="modal-header rounded-top">
                    <p class="text-white fs-4 fw-bold mb-0">Add Users</p>
                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="modal-body p-0">
                    <div class="ps-3 pe-3 pt-3">
                        <label for="room-name" class="form-label">Room Name</label>
                        <input type="text" class="form-control large-input" id="room-name" v-model="room.name" placeholder="Name of this room">
                    </div>

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
                        <button type="button" class="btn btn-primary ms-auto" :disabled="selected.length === 0" @click="save">
                            Save
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
    props: {
        room: {
            type: Object,
            required: true,
        },
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
            roomName: '',
        };
    },
    mounted() {
        this.toast = useToast();
    },
    methods: {
        toggle() {
            const modal = bootstrap.Modal.getOrCreateInstance("#" + this.modalId);

            this.load();

            modal.toggle();
        },
        load(page = 1, search = "") {
            const params = { page };

            if (search) {
                params.search = search;
            }

            params.exclude_existing = 1;
            params.room_id = this.room.id;

            axios.get("/chat/friends", { params })
                .then((response) => {
                    this.friends = response.data;
                })
                .catch((error) => {
                    this.toast.error("Failed to load friends.");
                });
        },
        save() {
            if (this.selected.length === 0) {
                this.toast.danger("Please select at least one friend to add to the chat");
                return;
            }

            const data = {
                users: this.selected,
                type: 'add'
            };

            if (this.roomName) {
                data.name = this.roomName;
            }

            axios.patch(`/chat/room/${this.room.id}`, data)
                .then(() => {
                    this.toast.success("Users added");

                    this.roomName = '';
                    this.selected = [];
                    this.load();

                    this.$emit("reload-rooms");
                    this.toggle();
                })
                .catch(() => {
                    this.toast.error("Failed to add users to the chat");
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
