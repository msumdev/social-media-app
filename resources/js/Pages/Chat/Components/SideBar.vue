<!-- Header Section -->
<template>
    <div class="col-lg-2 d-none d-lg-block">
        <div class="row">
            <div id="chat-sidebar" class="col-2 d-flex flex-column align-items-center border-end">
                <Icon :iconName="'bi-bell'" :show-tooltip="false" :class="'m-4'"></Icon>
                <Icon :iconName="'bi bi-gear'" :show-tooltip="false" :class="'m-4'"></Icon>
                <Icon :iconName="'bi-sun-fill'" :show-tooltip="false" :class="'m-4'" @click="$store.dispatch('toggleDayNight')" v-if="$store.state.isNightMode"></Icon>
                <Icon :iconName="'bi-moon'" :show-tooltip="false" :class="'m-4'" @click="$store.dispatch('toggleDayNight')" v-if="!$store.state.isNightMode"></Icon>

                <Link href="/" class="mt-auto" title="Logout">
                    <Icon :iconName="'bi-box-arrow-left'" :show-tooltip="false" :class="'m-4'"></Icon>
                </Link>
            </div>
            <div class="col-10 p-0">
                <div id="chat-user-background" class="d-flex flex-column align-items-stretch flex-shrink-0 vh-100 border-end">
                    <div class="d-flex align-items-center flex-shrink-0 p-3 text-decoration-none border-bottom shadow-sm justify-content-between">
                        <span id="chat-sidebar-title" class="d-flex justify-content-start fs-3">Messages</span>

                        <div class="d-flex">
                            <Icon :iconName="'bi-pencil-square'" :show-tooltip="false" @click="toggleNewChatModal"></Icon>
                        </div>
                    </div>

                    <div class="p-3 border-bottom">
                        <input type="text" class="form-control form-control-lg" id="chat-search-input" placeholder="Search...">
                    </div>

                    <div class="p-1 border-bottom">
                        <div class="row">
                            <div class="col-4 d-flex justify-content-center" v-if="menuLocation != null">
                                <Icon :tooltip="'Home'" :iconName="'bi-arrow-left-circle'" @click="loadDefaultRooms"></Icon>
                            </div>
                            <div class="col-4 d-flex justify-content-center">
                                <Icon
                                    :tooltip="'Favourites'"
                                    :iconName="'bi-star'"
                                    :background="'#ffdc82'"
                                    :color="'#484848'"
                                >
                                </Icon>
                            </div>
                            <div class="col-4 d-flex justify-content-center" v-if="menuLocation != 'groups'">
                                <Icon
                                    :tooltip="'Groups'"
                                    :iconName="'bi-people-fill'"
                                    :background="'#1da1f2'"
                                    :color="'#fff'"
                                    @click="loadGroups"
                                >
                                </Icon>
                            </div>
                            <div class="col-4 d-flex justify-content-center" v-if="menuLocation != 'archive'">
                                <Icon
                                    :tooltip="'Archive'"
                                    :iconName="'bi-archive'"
                                    :background="'#e4405f'"
                                    :color="'#fff'"
                                    @click="loadArchivedRooms"
                                >
                                </Icon>
                            </div>
                        </div>
                    </div>

                    <div class="p-3" v-if="rooms.data.length === 0">
                        <p class="text-center">No rooms to display.</p>
                    </div>

                    <div class="list-group list-group-flush overflow-auto vh-100">
                        <template v-for="(room, index) in rooms.data" v-if="rooms.data.length > 0">
                            <template v-for="(member) in room.members" v-if="room.type === 'direct'">
                                <!-- Direct Chat -->
                                <button
                                    class="list-group-item list-group-item-action py-3 lh-sm d-flex align-items-center justify-content-between border-bottom chat-message-list-item"
                                    @click="selectRoom(room)"
                                    v-if="member.recipient === true"
                                >
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative">
                                            <img id="chat-sidebar-picture" class="rounded-circle me-3" :src="member.profile_picture" alt="User Image">
                                            <span
                                                class="status-indicator position-absolute bottom-0 end-0 translate-middle-x p-2 border border-white rounded-circle bg-success"
                                                v-if="member.online"
                                            >
                                        </span>
                                        </div>
                                        <div class="chat-sidebar-profile">
                                            <p class="mb-1 fs-5"><strong>{{ member.name }}</strong></p>
                                            <span v-if="room.last_active" class="fs-5">Last active: {{ room.last_active }}</span>
                                            <small v-else class="fs-5">Some message</small>
                                        </div>
                                    </div>

                                    <div class="dropdown">
                                        <button type="button" id="moreDropdown" class="btn btn-link border-0 d-flex align-items-center justify-content-center rounded-circle icon-circle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical fs-3"></i>
                                        </button>
                                        <ul id="chat-sidebar-dropdown" class="dropdown-menu dropdown-menu-end" aria-labelledby="moreDropdown">
                                            <li><button class="dropdown-item fs-4" @click="deleteRoom">Delete Chat</button></li>
                                            <li><button class="dropdown-item fs-4">Mute Notifications</button></li>
                                            <li><button class="dropdown-item fs-4" @click="archiveRoom(room.id)" v-if="menuLocation != 'archive'">Archive</button></li>
                                            <li><button class="dropdown-item fs-4" @click="unarchiveRoom(room.id)" v-if="menuLocation === 'archive'">Unarchive</button></li>
                                        </ul>
                                    </div>
                                </button>
                            </template>
                            <!-- Group Chat -->
                            <button class="list-group-item list-group-item-action py-3 lh-sm d-flex align-items-center justify-content-between border-bottom chat-message-list-item" @click="selectRoom(room)" v-if="room.type === 'group'">
                                <div class="d-flex align-items-center">
                                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white me-3" style="width: 50px; height: 50px;">
                                    <i class="fa-solid fa-user-group"></i>
                                </span>
                                    <div class="chat-sidebar-profile">
                                        <p class="mb-1 fs-5"><strong>{{ room.name }}</strong></p>
                                        <span class="fs-5">{{ room.members.length }} members online</span>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button type="button" id="moreDropdown" class="btn btn-link border-0 d-flex align-items-center justify-content-center rounded-circle icon-circle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical fs-3"></i>
                                    </button>
                                    <ul id="chat-sidebar-dropdown" class="dropdown-menu dropdown-menu-end" aria-labelledby="moreDropdown">
<!--                                            <li><button class="dropdown-item fs-4" v-if="room.author.id === user.id">Delete Group</button></li>-->
<!--                                            <li><button class="dropdown-item fs-4" v-if="room.author.id != user.id">Leave Group</button></li>-->
                                        <li><button class="dropdown-item fs-4">Mute Notifications</button></li>
                                    </ul>
                                </div>
                            </button>
                        </template>
                        <div ref="chatLoadingDiv" class="chat-loading-div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <NewChatModal @chat-created="chatCreated" :ref="newChatModalRef"></NewChatModal>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import NewChatModal from "@/Shared/Sections/Chat/NewChatModal.vue";
import Icon from "@/Shared/Icon.vue";
import {v4 as uuidv4} from "uuid";
import axios from "axios";

export default {
    props: {
        rooms: {
            type: Array,
            required: true
        },
        user: {
            type: Object,
            required: true
        }
    },
    components: {
        Link,
        Icon,
        NewChatModal
    },
    data() {
        return {
            newChatModalRef: uuidv4(),
            observer: null,
            currentRoom: {
                messages: [],
            },
            menuLocation: null,
        }
    },
    mounted() {
        console.log(this.rooms);
    },
    methods: {
        toggleNewChatModal() {
            this.$refs[this.newChatModalRef].toggle();
        },
        chatCreated() {
            this.reloadRooms();

            this.toggleNewChatModal();
        },
        reloadRooms(page = null, archived = false, groups = false) {
            this.$emit('reload-rooms', page, archived, groups);
        },
        deleteRoom() {
            this.$emit('delete-room', this.currentRoom.id);
        },
        archiveRoom(id) {
            axios.patch('/chat/room/' + id, {
                archive: true
            })
                .then(() => {
                    this.rooms.data = this.rooms.data.filter(room => room.id !== id);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        unarchiveRoom(id) {
            axios.patch('/chat/room/' + id, {
                archive: false
            })
                .then(() => {
                    this.rooms.data = this.rooms.data.filter(room => room.id !== id);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        loadDefaultRooms() {
            this.menuLocation = null;

            this.reloadRooms();
        },
        loadArchivedRooms() {
            this.menuLocation = 'archive';

            this.reloadRooms(null, true);
        },
        loadGroups() {
            this.menuLocation = 'groups';

            this.reloadRooms(null, false, true);
        },
        selectRoom(room) {
            this.$emit('room-selected', room);
        }
    }
}
</script>
