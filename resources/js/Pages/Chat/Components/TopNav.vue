<template>
    <div class="col-12 col-lg-10 col-md-12 col-sm-12 p-4 d-flex align-items-center justify-content-between shadow-sm chat-header border-bottom" :class="{'blur-effect': !currentRoom}">
        <div class="d-flex align-items-center" v-if="currentRoom.type === 'direct'">
            <template v-for="(member) in currentRoom.members">
                <a :href="'/u/' + member.username" class="profile-img profile-img_48 mr_10" v-if="member.recipient === true">
                    <img :src="user.profile_picture" :alt="member.name + '\'s profile picture'">
                </a>
                <div class="post-profile-info" v-if="member.recipient === true">
                    <div class="d-flex align-items-center pb-3">
                        <a :href="'/u/' + member.username" class="site-icon f_700 title18">
                            {{ member.name }}
                        </a>
                        <p class="text-sm text-dark-grey pl_5 pr_5">{{ member.age }} . </p>
                        <p class="text-sm text-dark-grey" :title="created_at_title">{{ created_at_display }}</p>
                    </div>
                    <p class="text-sm text-dark-grey"><a :href="'/u/' + member.username + '/followers'">{{ member.follower_count }} followers</a></p>
                </div>
            </template>
        </div>
        <div class="d-flex align-items-center" v-if="currentRoom.type === 'group'">
            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white me-3" style="width: 50px; height: 50px;">
                <i class="fa-solid fa-user-group"></i>
            </span>
            <div>
                <h1 class="h3 mb-0"><strong>{{ currentRoom.name }}</strong></h1>
                <div class="d-flex align-items-center flex-wrap">
                    <a href="#"><p class="text-sm text-dark-grey me-4 mb-0">{{ currentRoom.members.length }} members</p></a>
                </div>
            </div>
        </div>

        <div class="more d-flex align-items-center">
            <!-- Image Cluster for Room Members -->
            <div class="image-cluster me-4" v-if="currentRoom.type === 'group' && currentRoom && currentRoom.members.length">
                <div class="profile-cluster">
                    <img v-for="(member, index) in currentRoom.members.slice(0, 5)"
                         :key="index"
                         :src="member.profile_picture"
                         :alt="member.name + '\'s profile picture'"
                         class="profile-img-small"
                         :title="member.name"
                    >
                </div>
                <span v-if="currentRoom.members.length > 5" class="more-count">+{{ currentRoom.members.length - 5 }}</span>
            </div>

            <Icon :iconName="'bi-info-circle'" :show-tooltip="false" @click="showChatInfoModal"></Icon>
            <Icon :iconName="'bi-person-fill-add'" :show-tooltip="false" @click="showAddUserModal"></Icon>
            <Icon :iconName="'bi-telephone'" :show-tooltip="false"></Icon>

            <div class="dropdown">
                <button type="button" id="moreDropdown" class="d-flex align-items-center justify-content-center" data-bs-toggle="dropdown" aria-expanded="false">
                    <Icon :iconName="'bi-three-dots-vertical'" :show-tooltip="false"></Icon>
                </button>
                <ul id="chat-navbar-dropdown" class="dropdown-menu dropdown-menu-end" aria-labelledby="moreDropdown">
                    <li><button class="dropdown-item fs-4" @click="deleteRoom">Delete</button></li>
<!--                    <li><button class="dropdown-item fs-4" v-if="currentRoom.type === 'group' && currentRoom.author.id === user.id" @click="purgeMessages">Purge Messages</button></li>-->
                    <li><button class="dropdown-item fs-4">Favourite</button></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import {Link} from "@inertiajs/vue3";
import Icon from "@/Shared/Icon.vue";

export default {
    components: {
        Link,
        Icon,
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        currentRoom: {
            type: Object,
            required: false
        },
    },
    methods: {
        deleteRoom() {
            this.$emit('delete-room', this.currentRoom.id);
        },
        showAddUserModal() {
            this.$emit('show-add-user-modal');
        },
        purgeMessages() {
            this.$emit('purge-messages', this.currentRoom.id);
        },
        showChatInfoModal() {
            this.$emit('show-chat-info-modal');
        }
    },
}
</script>

<style scoped>

</style>
