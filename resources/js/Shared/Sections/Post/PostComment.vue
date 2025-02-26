<template>
    <div class="comment-text">
        <div class="default-comment d-flex gap_x_12 border_t1 p_12 round12" :class="{ 'default-comment-post': comment.user.id === user.id }">
            <div class="profile-img profile-img_48 mr_10">
                <img :src="comment.user.profile_picture" alt="Avatar9">
            </div>
            <div class="default-comment-texts w-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <a href="#" class="text_15 f_700 site-icon">{{ comment.user.first_name }}</a>
                        <p class="text-dark-grey text_xsm f_400 pl_5"> {{ comment.user.age }} . </p>
                    </div>
                    <p class="text-dark-grey text_xsm f_500" :title="comment.created_at_title">{{ comment.created_at_display }}</p>
                </div>
                <p class="text_15 f_400 pb-3 pt_8" v-html="comment.content"></p>

                <AudioRecording v-for="asset in comment.assets" v-if="comment.assets.length > 0" :key="asset" :container-classes="$store.state.isNightMode ? 'bg-dark mb-4' : 'bg-white mb-4'" is-embedded="true" :path="asset.url" />

                <div class="d-flex align-items-center gap_x_12 text_xsm">
                    <button type="button" class="like_btn f_600 like-button">
                        <template v-if="comment.likes.length === 1">
                            <span class="like_num">{{ comment.likes.length }}</span> like
                        </template>
                        <template v-else>
                            <span class="like_num">{{ comment.likes.length }}</span> likes
                        </template>
                    </button>
                    <button type="button" class="translation_btn f_600">See translation</button>
                    <button type="button" class="comment_delete_btn f_600" v-if="user.id === comment.user.id" @click="deleteComment">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {useToast} from "vue-toastification";
import Reaction from "@/Shared/Sections/Post/Reaction.vue";
import axios from "axios";
import AudioRecording from "../../AudioRecording.vue";

export default {
    components: {
        Reaction,
		AudioRecording
    },
    props: {
		post: {
			type: Object,
			required: true
		},
        comment: {
            type: Object,
            required: true
        },
        user: {
            type: Object,
            required: true
        }
    },
	data() {
		return {
			toast: null
		}
	},
	mounted() {
		this.toast = useToast();
	},
	methods: {
		deleteComment() {
			axios.delete(`/post/${this.post._id}/comment/${this.comment._id}`)
				.then(response => {
					this.toast.success('Comment deleted');

					const post = this.$store.state.posts.find(post => post._id === this.post._id);

					post.comments.data = post.comments.data.filter(comment => comment._id !== this.comment._id);
				})
				.catch(error => {
					this.toast.error('An error occurred while deleting your comment');
					console.log(error);
				});
		}
	}
}
</script>
