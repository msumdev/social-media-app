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
                    <div class="col-lg-8 order-2 order-lg-1">
                        <NewPost :user="user" />

                        <!-- posts -->
                        <div class="posts">
                            <div class="post" v-for="post in $store.state.posts" :class="$store.state.isNightMode ? 'bg-black' : 'bg-white'" :key="post._id">
                                <Post @image-viewer-clicked="showImageViewer" @report-user="showReportPostModal" :post="post" :user="user" />
                            </div>
                        </div>

                        <div ref="loadingDiv" class="loading-div"></div>
                    </div>

                    <div class="col-lg-4 order-1 order-lg-2">
                        <Events />
                        <Updates />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <ImageViewerModal :post="imageViewerModalAsset.post" :commenting-disabled="true" :ref="imageViewerModal" />
    <ReportPostModal :post-id="reportPostId" :ref="reportPostModalRef" />
</template>

<script>
import { defineAsyncComponent } from 'vue';
import axios from "axios";
import { useToast } from "vue-toastification";
import SideBar from "@/Shared/Layout/SideBar.vue";
import TopNav from "@/Shared/Layout/TopNav.vue";
import PreLoader from "@/Shared/PreLoader.vue";
import ReportPostModal from "@/Shared/ReportPostModal.vue";
import ImageViewerModal from "@/Shared/ImageViewerModal.vue";
import { v4 as uuidv4 } from 'uuid';

export default {
    components: {
		PreLoader,
		SideBar,
		TopNav,
        ReportPostModal,
        ImageViewerModal,
        OnlineFriendsList: defineAsyncComponent(() => import('@/Shared/Layout/OnlineFriendsList.vue')),
        Events: defineAsyncComponent(() => import('@/Shared/Sections/Events.vue')),
        Updates: defineAsyncComponent(() => import('@/Shared/Sections/Updates.vue')),
        SiteAlert: defineAsyncComponent(() => import('@/Shared/Sections/SiteAlert.vue')),
        Post: defineAsyncComponent(() => import('@/Shared/Sections/Post.vue')),
        NewPost: defineAsyncComponent(() => import('@/Shared/NewPost.vue')),
    },
    data() {
        return {
            user: {
                profile_picture: {},
				settings: {
					render_media: null
				},
            },
            observer: null,
            nextPage: null,
            imageViewerModalAsset: {
                post: {
                    _id: null,
                    user: {
                        profile_picture: {}
                    },
                    assets: [],
					comments: []
                }
            },
            reportPostId: {},
            reportPostModalRef: uuidv4(),
            imageViewerModal: uuidv4()
        }
    },
    mounted() {
        this.getUser();
        this.getPosts();
		this.toast = useToast();

        this.observer = new IntersectionObserver(this.handleIntersection, {
            root: null,
            rootMargin: '0px',
            threshold: 1
        });
        const loadingDiv = this.$refs.loadingDiv;

        if (loadingDiv) {
            this.observer.observe(loadingDiv);
        }
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
        getPosts(url = null) {
            const apiUrl = url ? url : '/post';

            axios.get(apiUrl)
                .then(response => {
                    if (this.$store.state.posts.length > 0) {
                        this.$store.state.posts.push(...response.data.data);
                    } else {
                        this.$store.state.posts = response.data.data;
                    }

                    this.nextPage = response.data.next_page_url;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        async handleIntersection(entries, observer) {
            if (entries[0].isIntersecting) {
                if (this.nextPage) {
                    this.getPosts(this.nextPage);
                }
            }
        },
        showImageViewer(post) {
            this.imageViewerModalAsset.post = post;
            this.$refs[this.imageViewerModal].toggle();

            // this.$nextTick(() => {
            //     const imageViewerModal = document.getElementById('imageViewerModal');
            //
            //     if (!imageViewerModal) {
            //         return;
            //     }
            //
            //     const modal = new bootstrap.Modal(imageViewerModal).show();
            // });
        },
        showReportPostModal(postId) {
            this.reportPostId = postId;
            this.$refs[this.reportPostModalRef].toggle();
        },
	}
};
</script>
