<template>
    <PreLoader />
    <TopNav :user="user" />

    <div class="main_dashboard">
        <SideBar :user="user" />

        <!-- overlay -->
        <div class="overlay pb_32 d-lg-none d-block"> </div>
        <OnlineFriendsList />

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid">
				<!--                <SiteAlert />-->

				<div class="row">
					<div class="col-lg-7 order-2 order-lg-1">
						<Details :user="user" :profile="profile" v-if="user.id" />
					</div>

					<div class="col-lg-5 order-1 order-lg-2">
						<ProfilePictures />
						<ProfileViews :username="username" v-if="id === profile.id" />
					</div>
				</div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import SideBar from "@/Shared/Layout/SideBar.vue";
import TopNav from "@/Shared/Layout/TopNav.vue";
import PreLoader from "@/Shared/PreLoader.vue";
import axios from "axios";

export default {
    components: {
        PreLoader,
        SideBar,
        TopNav,
        OnlineFriendsList: defineAsyncComponent(() => import('@/Shared/Layout/OnlineFriendsList.vue')),
        SiteAlert: defineAsyncComponent(() => import('@/Shared/Sections/SiteAlert.vue')),
		Details: defineAsyncComponent(() => import('@/Shared/Sections/Profile/Details.vue')),
		ProfilePictures: defineAsyncComponent(() => import('@/Shared/Sections/Profile/ProfilePictures.vue')),
		ProfileViews: defineAsyncComponent(() => import('@/Shared/Sections/Profile/ProfileViews.vue')),
    },
    data() {
        return {
            user: {
                profile: {},
                profile_picture: {},
                settings: {},
            },
            profile: {
                profile: {},
                profile_picture: {},
                settings: {},
            },
			username: "",
			id: null,
        }
    },
    mounted() {
        this.username = this.$page.props.username;
		this.id = this.$page.props.id;

		this.getUser();
    },
	methods: {
		getUser() {
			axios.get(`/u/${this.username}?json=1`)
				.then(response => {
					this.user = response.data.user;
					this.profile = response.data.profile;
				})
				.catch(error => {
					this.toast.error("Failed to fetch languages");
				});
		}
	}
};
</script>
