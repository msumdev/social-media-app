<template>
    <PreLoader />

    <div class="container-fluid">
        <div class="row bg-white rounded-2 shadow-sm d-flex flex-column chat-content">
            <!-- SideBar Section -->
            <SideBar
                :rooms="rooms"
                :user="user"
                @room-selected="roomSelected"
                @delete-room="deleteRoom"
                @reload-rooms="getRooms"
            />
            <!-- TopNav Section -->
            <TopNav
                :user="user"
                :current-room="currentRoom"
                @delete-room="deleteRoom"
                @show-add-user-modal="showAddUserModal"
                @purge-messages="purgeMessages"
                @show-chat-info-modal="showChatInfoModal"
            />
            <!-- Messages Section -->
            <MessageSection
                :current-room="currentRoom"
                :user="user"
                @load-more-messages="loadMoreMessages"
                @report-modal-clicked="onReportMessageModal"
            />
            <!-- Input Section -->
            <NewMessage
                @chat-input="onNewMessage"
                @submit-message="onMessageSubmitted"
                :current-room="currentRoom"
                :user="user"
            />
        </div>
    </div>

    <ReportChatMessageModal :ref="reportMessageModalRef" />
    <AddUserModal
        :ref="addUserModalRef"
        :room="currentRoom"
        @reload-rooms="getRooms"
    />
    <ChatInfoModal
        :ref="chatInfoModalRef"
        :room-id="currentRoom.id"
    />
</template>

<script>
import SideBar from '@/Pages/Chat/Components/SideBar.vue'
import TopNav from '@/Pages/Chat/Components/TopNav.vue'
import MessageSection from '@/Pages/Chat/Components/MessageSection.vue'
import ReportChatMessageModal from '@/Pages/Chat/Components/ReportChatMessageModal.vue'
import NewMessage from '@/Shared/Sections/Chat/NewMessage.vue'
import PreLoader from "@/Shared/PreLoader.vue";
import AddUserModal from "@/Pages/Chat/Components/AddUserModal.vue";
import ChatInfoModal from "@/Pages/Chat/Components/ChatInfoModal.vue";

import axios from "axios";
import { Link } from "@inertiajs/vue3";
import {v4 as uuidv4} from "uuid";
import { emitter } from '@/websocket.js';

export default {
    components: {
        SideBar,
        TopNav,
        MessageSection,
        ReportChatMessageModal,
        Link,
		PreLoader,
        NewMessage,
        AddUserModal,
        ChatInfoModal,
    },
    data() {
        return {
            user: {
                profile_picture: {},
                settings: {
                    render_media: null
                },
            },
            nextPage: null,
            currentRoom: {
                name: '',
                moreMessagesLink: null,
                messages: [],
                members: [],
                author: {},
                recipient: {},
                type: 'direct',
            },
            loading: true,
            reportMessageId: null,
            reportMessageModalRef: uuidv4(),
            addUserModalRef: uuidv4(),
            chatInfoModalRef: uuidv4(),
            chatSection: null,
            notifications: [],
            rooms: {
                data: [],
                next_page_url: null,
            },
        }
    },
    mounted() {
        this.getUser();
        this.getRooms();
        this.chatSection = document.getElementById('chat-section');

        emitter.on('room:new-message', (event) => {
            if (event.data.room_id === this.currentRoom.id) {
                this.currentRoom.messages.push(event.data);

                this.setDivHeight();
            }
        });

        emitter.on('room:purge', (event) => {
            const roomId = event.message.room_id;

            if (this.currentRoom.id === roomId) {
                this.currentRoom.messages = [];
            }

            this.setDivHeight();
        });

        emitter.on('room:message-deleted', (event) => {
            const roomId = event.message.room_id;
            const messageId = event.message.message_id;

            if (this.currentRoom.id === roomId) {
                this.currentRoom.messages = this.currentRoom.messages.filter(message => message.id !== messageId);
            }

            this.setDivHeight();
        });
    },
    methods: {
        getUser() {
            axios.get('/user')
                .then(response => {
                    this.user = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        onNewMessage(message) {
            console.log("User is typing");
        },
        onMessageSubmitted(output) {
            const message = output.content;

            axios.post(`/chat/room/${this.currentRoom.id}/message`, {
                content: message,
            })
                .then(response => {
                    this.currentRoom.messages.push(response.data);

                    this.$nextTick(() => {
                        this.setDivHeight();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        },
        groupMessagesByUser(messages) {
            const grouped = [];
            let currentGroup = [];

            messages.forEach((message, index) => {
                if (
                    currentGroup.length === 0 ||
                    currentGroup[currentGroup.length - 1].author.id === message.author.id
                ) {
                    currentGroup.push(message);
                } else {
                    grouped.push(currentGroup);
                    currentGroup = [message];
                }
            });

            if (currentGroup.length > 0) {
                grouped.push(currentGroup);
            }

            return grouped;
        },
        roomSelected(room) {
            // if (this.currentRoom.id === room.id) return;

            this.currentRoom = room;

            this.loadRoomMessages(room.id);
        },
        loadRoomMessages(roomId) {
            axios.get(`/chat/room/${roomId}/message`)
                .then(response => {
                    this.currentRoom.messages = response.data.data.reverse();
                    this.currentRoom.moreMessagesLink = response.data.links.next;

                    this.$nextTick(() => {
                        this.setDivHeight();
                    });
                })
                .catch(error => {
                    console.error(error);
                });
        },
        deleteRoom(roomId) {
            axios.delete(`/chat/room/${roomId}`)
                .then(() => {
                    this.currentRoom = {
                        messages: [],
                        author: {},
                        recipient: {

                        },
                    };
                })
                .catch(error => {
                    console.error(error);
                });
        },
        onReportMessageModal(messageId) {
            this.$refs[this.reportMessageModalRef].toggle(this.currentRoom.id, messageId);
        },
        setDivHeight() {
            this.$nextTick(() => {
                this.chatSection.scrollTo({
                    top: this.chatSection.scrollHeight,
                    behavior: "smooth"
                });
            });
        },
        loadMoreMessages() {
            if (this.currentRoom.moreMessagesLink) {
                axios.get(this.currentRoom.moreMessagesLink)
                    .then(response => {
                        const messages = response.data.data.reverse();
                        const currentHeight = this.chatSection.scrollHeight;

                        this.currentRoom.messages = messages.concat(this.currentRoom.messages);
                        this.currentRoom.moreMessagesLink = response.data.links.next;

                        this.$nextTick(() => {
                            this.chatSection.scrollTop = this.chatSection.scrollHeight - currentHeight;
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },
        showAddUserModal() {
            this.$refs[this.addUserModalRef].toggle();
        },
        getRooms(nextPage = null, archived = false, groups = false) {
            const page = nextPage ? nextPage : '/chat/room';
            const params = {};

            params.type = 'direct';

            if (archived) {
                params.type = 'archived';
            }

            if (groups) {
                params.type = 'group';
            }

            axios.get(page, { params })
                .then(response => {
                    this.rooms = response.data;
                    this.nextPage = response.data.next_page_url;

                    if (this.rooms.data.length > 0) {
                        const latestRoom = this.rooms.data[0];

                        this.getRoom(latestRoom.id);
                        this.loadRoomMessages(latestRoom.id);
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        },
        getRoom(id) {
            if (id === this.currentRoom.id) return;

            axios.get(`/chat/room/${id}`)
                .then(response => {
                    this.currentRoom = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        purgeMessages(id) {
            axios.patch(`/chat/room/${id}`, {
                purge: true
            })
                .then(() => {
                    this.currentRoom.messages = [];
                })
                .catch(error => {
                    console.error(error);
                });
        },
        showChatInfoModal() {
            this.$refs[this.chatInfoModalRef].toggle();
        },
	}
};
</script>
