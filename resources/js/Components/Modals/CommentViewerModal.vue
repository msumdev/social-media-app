<template>
    <TransitionRoot as="template" :show="open">
        <Dialog class="relative z-60" @close="minimize">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-950/80 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel ref="modalRef" class="relative transform overflow-x-auto no-scrollbar rounded-lg bg-white dark:bg-gray-900 text-left shadow-xl transition-all w-full max-w-full sm:max-w-3xl md:max-w-4xl lg:max-w-5xl max-h-[calc(100vh-20px)]">
                            <div class="py-4">
                                <div class="bg-white text-black rounded-lg shadow-sm p-3 dark:bg-gray-900 dark:text-white">
                                    <div class="flex justify-between">
                                        <div class="flex items-center space-x-4 rounded-lg text-black dark:text-white">
                                            <img :src="post.user.profile_picture" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover shadow-md cursor-pointer" />
                                            <div class="flex flex-col">
                                                <p class="font-bold text-sm text-blue-600 dark:text-blue-500">{{ post.user.first_name }}</p>
                                                <p class="text-sm text-gray-600 cursor-pointer hover:text-blue-600 dark:text-blue-500">@{{ post.user.username }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-4">
                                            <p class="text-xs text-gray-500" :title="post.created_at_title">{{ post.created_at_display }}</p>
                                            <div class="relative">
                                                <div class="flex content-center">
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
                                                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" v-if="post.user.id === userStore.user.id" @click="this.$emit('post:deleted', post.id)">Delete</a>
                                                        </nav>
                                                    </fwb-dropdown>

                                                    <fwb-button role="button" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600 cursor-pointer" @click="$emit('modal:closed')">
                                                        <i class="fa-solid fa-xmark text-xl"></i>
                                                    </fwb-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="my-4" v-html="post.content"></p>

                                    <fwb-carousel class="mt-4 carousel-image" :pictures="post.image_assets" v-if="post.image_assets.length > 0" no-indicators v-bind:no-controls="post.image_assets.length === 1" />
                                </div>

                                <div class="w-full md:flex-1">
                                    <editor ref="editor" v-model="form" @submit="submit" :id="'comment-editor'"></editor>
                                </div>

                                <div v-if="comments.data.length > 0">
                                    <div class="bg-white dark:bg-gray-900 sm:flex sm:items-start flex-col space-y-2 px-4 py-4" v-for="(comment, index) in comments.data">
                                        <div class="flex justify-between items-center text-black dark:text-white w-full">
                                            <div class="flex items-center space-x-4">
                                                <div class="relative">
                                                    <img :src="comment.user.profile_picture" alt="Profile Picture"
                                                         class="w-10 h-10 rounded-full object-cover shadow-md cursor-pointer" />
                                                    <span class="absolute bottom-0 right-0 block w-2.5 h-2.5 bg-gray-400 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-sm text-blue-600 dark:text-blue-500">
                                                        {{ comment.user.first_name }} {{ comment.user.last_name }}
                                                    </div>
                                                    <div class="text-sm text-stone-500 dark:text-stone-400 hover:text-blue-500 cursor-pointer">
                                                        @{{ comment.user.username }}
                                                    </div>
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
                                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Report</a>
                                                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white" v-if="comment.user.id === userStore.user.id" @click="remove(comment.id)">Delete</a>
                                                    </nav>
                                                </fwb-dropdown>
                                            </div>
                                        </div>
                                        <div class="w-full" :class="index < (comments.data.length - 1) ? 'py-2 border-b border-gray-100 dark:border-gray-700' : ''">
                                            <div class="grid w-full">
                                                <p class="text-sm text-gray-700 dark:text-gray-300" v-html="comment.content"></p>
                                            </div>

                                            <div class="flex items-center justify-between mt-2">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex text-gray-600 hover:text-blue-600">
                                                        <i class="fas fa-share mr-1 cursor-pointer"></i>
                                                        <button class="flex items-center text-xs cursor-pointer">
                                                            Share
                                                        </button>
                                                    </div>

                                                    <div class="flex text-gray-600 hover:text-blue-600">
                                                        <i class="fab fa-facebook-messenger mr-1 cursor-pointer"></i>
                                                        <button class="flex items-center text-xs cursor-pointer">
                                                            Send
                                                        </button>
                                                    </div>

                                                    <div class="flex text-gray-600">
                                                        <span class="flex items-center text-xs" :title="comment.created_at_title">{{ comment.created_at_display }}</span>
                                                    </div>
                                                </div>
                                                <div class="items-center space-x-1 hidden md:flex">
                                                    <div class="flex" v-for="reaction in comment.reactions">
                                                        <button
                                                            class="flex items-center px-2 py-1 text-sm rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                                                            :class="reaction.user_ids.includes(userStore.user.id)
                                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-white'
                                                        : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200'"
                                                            @click="react(reaction.reaction, reaction.reaction_unicode, comment)"
                                                        >
                                                            {{ reaction.user_id }}
                                                            {{ reaction.reaction }} <span class="ml-1">{{ reaction.count }}</span>
                                                        </button>
                                                    </div>

                                                    <emoji-picker @emoji-added="(event) => react(event.i, event.u, comment)" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-center py-2" v-if="this.comments.links.next">
                                        <div role="status" ref="more-messages">
                                            <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                            </svg>
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" v-else>
                                    <p class="text-sm italic">No comments yet. Be the first to share your thoughts!</p>
                                </div>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import EmojiPicker from '@/Components/Utilities/EmojiPicker.vue'
import { FwbButton, FwbDropdown, FwbCarousel } from "flowbite-vue";
import { UserStore } from '@/Stores/userStore';
import Editor from '@/Components/Utilities/Editor.vue'
import { ToastStore } from "@/Stores/toastStore.js";
import axios from "axios";
import {useForm} from "@inertiajs/vue3";

export default {
    components: {
        FwbButton,
        FwbDropdown,
        FwbCarousel,
        Dialog,
        DialogPanel,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        EmojiPicker,
        Editor,
    },
    props: {
        modelValue: {
            type: Boolean,
            required: true,
        },
        post: {
            type: Object,
            required: true,
        },
        comments: {
            type: Object,
            required: true,
        },
        visible: {
            type: Boolean,
            required: true,
        }
    },
    data() {
        return {
            userStore: UserStore(),
            toast: ToastStore(),
            observer: null,
            form: useForm({
                text: null,
                html: null,
                mentions: [],
                hashtags: [],
                images: [],
                audio: [],
                editor: {},
            }),
        }
    },
    computed: {
        open: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    watch: {
        visible(value) {
            if (value) {
                this.setup();
            }
        }
    },
    methods: {
        setup() {
            this.observer = new IntersectionObserver(([entry]) => {
                if (entry.isIntersecting) {
                    this.load()
                }
            }, {
                root: null,
                rootMargin: '0px',
                threshold: 1.0
            });

            this.$nextTick(() => {
                const moreMessagesRef = this.$refs['more-messages']

                this.observer.observe(moreMessagesRef);
            });
        },
        minimize() {
            this.open = false;
        },
        react(reaction_icon, reaction_unicode, comment) {
            axios.post(`/post/${this.post.id}/${comment.id}/react`, {
                reaction: reaction_icon,
                reaction_unicode: reaction_unicode,
            })
            .then(response => {
                comment.reactions = response.data;
            })
            .catch(error => {
                this.toast.warning('I couldn\'t react to that!?')
            });
        },
        submit() {
            axios.post(`/post/${this.post.id}/comment`, this.form.data())
                .then(response => {
                    this.comments.data.unshift(response.data);

                    this.$emit('comment:added');
                })
                .catch(error => {
                    this.toast.warning('I couldn\'t add your post!')
                });
        },
        remove(id) {
            axios.delete(`/post/${this.post.id}/comment/${id}`)
                .then(response => {
                    this.$emit('comment:deleted', id);
                })
                .catch(error => {
                    this.toast.warning('Jeez, I couldn\'t delete this comment')
                });
        },
        load() {
            setTimeout(() => {
                const next = this.comments.links.next;

                if (next) {
                    axios.get(next)
                        .then(response => {
                            this.$emit('comments:merge', response)
                        })
                        .catch(error => {
                            this.toast.warning('Something has gone wrong loading comments for this post')
                        })
                }
            }, 1250);
        }
    }
}
</script>
