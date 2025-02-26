<template>
    <!-- Modal -->
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + '-label'">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-3 shadow-sm border-0">
                <ul class="nav nav-tabs" id="infoTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">General Info</button>
                    </li>
                    <li class="nav-item" role="presentation" v-if="roomInfo.type === 'group'">
                        <button class="nav-link ms-3 rounded-top" id="owners-tab" data-bs-toggle="tab" data-bs-target="#owners" type="button" role="tab">Owners</button>
                    </li>
                </ul>

                <div class="modal-body p-4">
                    <!-- Tab Content -->
                    <div class="tab-content" id="infoTabContent">
                        <!-- General Info Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div v-if="roomInfo.type === 'group'" class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-semibold">Name</div>
                                <div class="text-end text-dark fw-bold" id="name">{{ roomInfo.name }}</div>
                            </div>
                            <hr v-if="roomInfo.type === 'group'">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-semibold">Room Type</div>
                                <small class="text-end" id="roomType">
                                    {{ roomInfo.type === 'direct' ? 'Direct Chat' : 'Group Chat' }}
                                </small>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-semibold">Member Count</div>
                                <small class="text-end" id="memberCount">{{ roomInfo.member_count }}</small>
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="fw-semibold">Message Count</div>
                                <small class="text-end" id="messageCount">{{ roomInfo.message_count }}</small>
                            </div>
                        </div>

                        <!-- Owners Tab -->
                        <div class="tab-pane fade" id="owners" role="tabpanel" v-if="roomInfo.owners.data.length > 0">
                            <PaginatedUserTable
                                :data="roomInfo.owners"
                                :links="roomInfo.owners.links"
                                @update-user-table-data="updateUserTableData"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from "vue-toastification";
import axios from "axios";
import { v4 as uuidv4 } from "uuid";
import PaginatedUserTable from "@/Shared/PaginatedUserTable.vue";

export default {
    components: {
        PaginatedUserTable,
    },
    props: {
        roomId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            modalId: uuidv4(),
            toast: useToast(),
            roomInfo: {
                owners: {
                    data: []
                }
            }
        };
    },
    methods: {
        toggle() {
            const modal = bootstrap.Modal.getOrCreateInstance("#" + this.modalId);

            this.getRoomInfo();

            modal.toggle();
        },
        getRoomInfo() {
            axios.get(`/chat/room/${this.roomId}/info`)
                .then(response => {
                    this.roomInfo = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        fetchOwners(url) {
            if (!url) return;

            axios.get(url)
                .then(response => {
                    this.roomInfo.owners = response.data.owners;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        updateUserTableData(data) {
            this.roomInfo.owners = data;
        },
    },
};
</script>


<style scoped>
.modal-body {
    max-height: 500px;
    overflow-y: auto;
    background: #fff;
}

.nav-link {
    background: #fff
}

.night-mode .modal-body {
    background: var(--dark-mode-colour);
}

.night-mode .nav-link {
    background: var(--dark-mode-colour);
    color: #fff;
    border: none;
}

.night-mode .nav-link:hover {
    background: var(--blue);
}

.night-mode .nav {
    border: none;
}

.nav-link.active {
    background: var(--blue) !important;
    border-top-color: var(--blue) !important;
    border-left-color: var(--blue) !important;
    border-right-color: var(--blue) !important;
    border-bottom-color: #fff !important;
    color: #fff;
}

.modal-content {
    background-color: transparent;
}
</style>
