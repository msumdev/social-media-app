<template>
    <!-- Modal -->
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + '-label'">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body p-0" v-if="post._id">
                    <div class="row" :class="$store.state.isNightMode ? 'bg-dark' : 'bg-white'">
                        <div :class="showCommentSection ? 'col-md-6 p-0' : 'col-md-12 p-0'" >
                            <template v-if="post.assets">
                                <img
                                    :src="post.assets[0].url"
                                    :alt="post.user.first_name + '\'s image'"
                                    class="img-fluid h-100 w-100"
                                    style="object-fit: cover;"
                                    v-if="post.assets.length === 1"
                                >
                                <div :id="'image-viewer-' + post.id" class="carousel slide post-images h-100 w-100" v-else>
                                    <div class="carousel-inner h-100">
                                        <div class="carousel-item h-100 bg-dark" :class="{'active' : index === 0}" v-for="(asset, index) in imageAssets">
                                            <img
                                                :src="asset.url"
                                                :alt="post.user.first_name + '\'s image'"
                                                class="img-fluid modal-image h-100 w-100"
                                                style="object-fit: contain; max-height: 500px; width: 100%; height: auto;"
                                            >
                                        </div>
                                    </div>
                                    <button class="carousel-control-prev" type="button" :data-bs-target="'#image-viewer-' + post.id" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" :data-bs-target="'#image-viewer-' + post.id" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div class="col-md-6 p-4 d-flex flex-column" v-if="showCommentSection">
                            <div class="row mb-3 align-items-center justify-content-between">
                                <!-- User Info with Profile Picture -->
                                <div class="col-auto d-flex align-items-center">
                                    <a class="profile-img profile-img_48 mr_10">
                                        <img :src="post.user.profile_picture" :alt="post.user.first_name + '\'s profile picture'" class="rounded-circle me-2">
                                    </a>
                                    <div>
                                        <h6 class="mb-0">{{ post.user.first_name }} {{ post.user.last_name }}</h6>
                                        <p class="text-muted mb-0 small">Posted on: <span class="fw-bold" :title="post.created_at_title">{{ post.created_at_display }}</span></p>
                                    </div>
                                </div>

                                <!-- Close Button -->
                                <div class="col-auto">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                            </div>

                            <!-- Reactions (without emojis) -->
                            <div class="row mb-3">
                                <div class="col">
                                    <p v-html="processedContent"></p>
                                </div>
                            </div>

                            <!-- Scrollable Comments Section -->
                            <div class="row flex-grow-1 overflow-auto mb-3" style="max-height: 250px;">
                                <div class="col">
                                    <div class="comment-box">
                                        <div class="default-post-wrap" v-for="comment in comments" v-if="comments.length > 0">
                                            <PostComment :comment="comment" :user="post.user" />
                                        </div>
                                        <div class="default-post-wrap" v-else>
                                            <p class="text-center text-muted">No comments yet.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add New Comment Section (fixed height and non-expandable) -->
                            <div class="row" v-if="!commentingDisabled">
                                <div class="col">
                                    <div class="quick-post-top">
                                        <a href="#" class="profile-img profile-img_48 mr_10">
                                            <img :src="post.user.profile_picture" :alt="post.user.first_name + '\'s profile picture'">
                                        </a>
                                        <div class="quick-post-text position-relative ml_12">
                                            <input type="text" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100 form-control" placeholder="Write a comment ...">
                                            <div class="position-absolute border_t1_left position_center_y right_0 pr_16 d-flex align-items-center">
                                                <button type="button" class="site-icon pl_10 border_left">
                                                    <img src="/images/face-smile.svg" class="icon20" alt="face-smile">
                                                </button>
                                                <button type="button" class="f_700 text-blue text-sm pl_10 site-icon comment_send">Send</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import PostComment from "@/Shared/Sections/Post/PostComment.vue";
import {v4 as uuidv4} from "uuid";

export default {
    components: {
		PostComment
	},
    props: {
        post: {
            type: Object,
            required: true
        },
        showCommentSection: {
            type: Boolean,
            default: true
        },
        commentingDisabled: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            modalId: uuidv4(),
            comments: {},
            newCommentText: '',
        }
    },
    computed: {
        processedContent() {
            if (!this.post.content) return '';

            this.post.content = this.post.content.replace(/<p><\/p>/g, '<p>&nbsp;</p>');

            return this.post.content;
        },
        imageAssets() {
            if (!this.post.assets) return [];

            return this.post.assets.filter(asset => asset.type === 'post-images');
        }
    },
    mounted() {
        if (this.showCommentSection && this.post._id) {
            this.getComments();
        }
    },
    methods: {
        toggle() {
            const modal = bootstrap.Modal.getOrCreateInstance('#' + this.modalId);
            modal.show();
        },
        addNewComment() {},
        getComments() {
            axios.get(`/post/${this.post._id}/comments`)
                .then(response => {
                    this.comments = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>

<style scoped>

</style>
