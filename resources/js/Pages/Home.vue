<template>
    <div class="flex flex-col md:flex-row p-4">
        <div class="flex-1 space-y-4 md:mr-4">
            <div class="bg-white text-black dark:bg-gray-900 dark:text-white rounded-xl shadow-md md:px-4 md:py-2">
                <div class="flex flex-col md:flex-row items-start md:items-center">
                    <div class="flex-shrink-0 hidden md:block">
                        <img
                            :src="userStore.user.profile_picture"
                            alt="Profile Picture"
                            class="w-14 h-14 rounded-full object-cover shadow-md border border-gray-300 dark:border-gray-700"
                        />
                    </div>

                    <div class="w-full md:flex-1">
                        <editor ref="editor" v-model="form" @submit="onSubmit"></editor>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4">
                    <recording class="col-span-1" v-for="(src, index) in form.audio" v-if="form?.audio?.length > 0" :key="index" :src="src" @remove-audio="removeAudio(index)"></recording>

                    <div class="col-span-1">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div v-for="(image, index) in form.images" :key="index" class="relative">
                                <button @click="form.images.splice(index, 1)" type="button" class="absolute top-2 right-2 inline-flex items-center justify-center rounded-full bg-white text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 p-1.5 shadow cursor-pointer" aria-label="Remove image">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                <img class="mx-auto aspect-[3/2] w-full max-w-sm rounded-lg object-cover" :src="image.data" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center" role="status" v-if="!postsLoaded">
                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>

            <div v-for="post in posts" :key="post.id" class="bg-white text-black rounded-lg shadow-sm p-3 dark:bg-gray-900 dark:text-white">
                <div class="flex justify-between">
                    <div class="flex items-center space-x-4 rounded-lg text-black dark:text-white">
                        <img :src="post.user.profile_picture" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover shadow-md cursor-pointer" />
                        <div class="flex flex-col">
                            <p class="font-bold text-sm text-blue-600 cursor-pointer dark:text-blue-500">{{ post.user.first_name }}</p>
                            <p class="text-xs text-gray-500" :title="post.created_at_display">{{ post.created_at_title }}</p>
                        </div>
                    </div>
                    <div class="relative">
                        <fwb-dropdown class="z-40" align-to-end>
                            <template #trigger>
                                <fwb-button role="button" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 cursor-pointer">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
                                        <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
                                    </svg>
                                </fwb-button>
                            </template>
                            <nav class="py-2 text-sm text-gray-700 dark:text-gray-200 w-[9.5rem]">
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Favourite</a>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Report</a>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" v-if="post.user.id === userStore.user.id" @click="deletePost(post.id)">Delete</a>
                            </nav>
                        </fwb-dropdown>
                    </div>
                </div>
                <p class="mt-4" v-html="post.content"></p>

                <fwb-carousel class="mt-4 carousel-image cursor-pointer" :pictures="post.image_assets" v-if="post.image_assets.length > 0" no-indicators v-bind:no-controls="post.image_assets.length === 1" @click="loadComments(post)" />

                <hr class="h-px mt-4 mb-4 bg-gray-200 border-0 dark:bg-gray-700">
                <div class="flex gap-8">
                    <div class="cursor-pointer group" @click="likePost(post)">
                        <i class="fa-regular fa-thumbs-up text-blue-500 group-hover:text-blue-300 text-lg hover:cursor-pointer" :class="post.liked_by_user ? 'fa-solid' : 'fa-regular'"></i> <span class="text-black dark:text-white text-sm">{{ post.like_count }} {{ (post.like_count === 1) ? 'Like' : 'Likes' }}</span>
                    </div>
                    <div class="hover:cursor-pointer group" @click="loadComments(post)">
                        <i class="fa-regular fa-comments text-blue-500 group-hover:text-blue-300 text-lg"></i> <span class="text-black dark:text-white text-sm">{{ post.comment_count }} {{ (post.comment_count === 1) ? 'Comment' : 'Comments' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-96 bg-white rounded-lg p-4 shadow-sm h-fit text-black dark:bg-gray-900 dark:text-white hidden lg:block">
            <h1 class="text-2xl font-bold text-blue-500">Events</h1>
            <hr class="h-px mt-4 mb-4 bg-gray-200 border-0 dark:bg-gray-700">

            <code>Some events here</code>
        </div>
    </div>

    <comment-viewer-modal
        :visible="commentModalVisible"
        :post="post"
        :comments="comments"
        @update:comments="updateComments"
        @post:deleted="deletePost"
        @comment:deleted="deleteComment"
        @comment:added="addedComment"
        @comments:merge="mergeComments"
        @modal:closed="commentModalVisible = false"
        v-model="commentModalVisible"
    ></comment-viewer-modal>
</template>

<script>
import { UserStore } from '@/Stores/userStore';
import Editor from '@/Components/Utilities/Editor.vue'
import Recording from '@/Components/Utilities/Recording.vue'
import { ToastStore } from "@/Stores/toastStore.js";
import CommentViewerModal from "@/Components/Modals/CommentViewerModal.vue";
import { FwbDropdown, FwbCarousel, FwbToast, FwbButton } from 'flowbite-vue'
import axios from "axios";
import {useForm} from "@inertiajs/vue3";

export default {
    components: {
        FwbDropdown,
        FwbCarousel,
        FwbToast,
        FwbButton,
        Editor,
        Recording,
        CommentViewerModal,
    },
    data() {
        return {
            userStore: UserStore(),
            post: {},
            posts: [],
            comments: {},
            postsLoaded: false,
            form: useForm({
                text: null,
                html: null,
                mentions: [],
                hashtags: [],
                images: [],
                audio: [],
                editor: {},
            }),
            audioSources: [],
            toast: ToastStore(),
            commentModalVisible: false,
        }
    },
    mounted() {
        this.getPosts();
    },
    methods: {
        getPosts(url = null, reset = false) {
            const apiUrl = url ? url : '/post';

            axios.get(apiUrl)
                .then(response => {
                    if (this.posts.length > 0 && !reset) {
                        this.posts.push(...response.data.data);
                    } else {
                        this.posts = response.data.data;
                    }

                    this.nextPage = response.data.next_page_url;
                    this.postsLoaded = true;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        likePost(post) {
            axios.post(`/post/likes/${post.id}`)
                .then(response => {
                    this.posts.forEach((post, index) => {
                        if (post.id === response.data.id) {
                            this.posts[index].like_count = response.data.like_count;
                            this.posts[index].liked_by_user = response.data.liked_by_user;
                        }
                    });
                })
                .catch(error => {
                    console.log(error);
                });
        },
        deletePost(id) {
            if (confirm('Are you sure you want to delete this post?') === false) {
                return;
            }

            axios.delete(`/post/${id}`)
                .then(response => {
                    this.toast.success('Post deleted');

                    this.posts = this.posts.filter(p => p.id != id);

                    if (this.commentModalVisible) {
                        this.commentModalVisible = false;
                    }
                })
                .catch(error => {
                    this.toast.warning('Something went wrong while deleting your post');
                });
        },
        removeAudio(index) {
            this.form.audio = this.form.audio.filter((audio, audioIndex) => audioIndex !== index);
        },
        loadComments(post) {
            this.post = post;

            axios.get(`/post/${post.id}/comments`)
                .then(response => {
                    this.comments = response.data;

                    this.commentModalVisible = true;
                })
                .catch(error => {
                    this.toast.warning('Something has gone wrong loading comments for this post')
                })
        },
        updateComments(comments) {
            this.comments = comments;
        },
        onSubmit() {
            this.form.post(`/post`, {
                onSuccess: () => {
                    this.form.reset();
                },
                onError: () => {
                    this.toast.danger('Something went wrong!', 5000)
                },
            });
        },
        deleteComment(id) {
            const post = this.posts.find(p => p.id === this.post.id);

            post.comment_count--;

            this.comments.data = this.comments.data.filter((comment) => comment.id !== id);
        },
        addedComment() {
            const post = this.posts.find(p => p.id === this.post.id);

            post.comment_count++;
        },
        mergeComments(response) {
            this.comments.links = response.data.links;

            this.comments.data.push(...response.data.data);
        }
    }
}
</script>
