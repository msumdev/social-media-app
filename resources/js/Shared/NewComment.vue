<template>
    <div class="quick-post-top pt-4">
        <a href="#" class="profile-img profile-img_48">
            <img :src="post.user.profile_picture" :alt="post.user.first_name + '\'s profile picture'">
        </a>
        <div class="quick-post-text position-relative">
            <div class="input-group ps-4">
                <Editor ref="editor" :id="'new-comment-' + this.post._id" @editor-input="onEditorInput" />

                <span class="input-group-text" :class="$store.state.isNightMode ? 'bg-dark' : 'bg-light'">
                    <Recorder @audio-recorded="onAudioRecorded" />
                </span>
                <span class="input-group-text" :class="$store.state.isNightMode ? 'bg-dark' : 'bg-light'">
                    <button type="button" class="site-icon" @click="addNewComment">
                        <i class="fa-solid fa-reply fa-xl" style="color: #4182EB"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>

    <div class="upload-area" v-if="recordedAudios.length > 0">
        <AudioRecording v-for="audio in recordedAudios" v-if="recordedAudios.length > 0" :key="audio" :audio-src="audio" @delete-audio="deleteAudio(audio)" />
    </div>
</template>

<script>
import {useToast} from "vue-toastification";
import { defineAsyncComponent } from 'vue';
import axios from "axios";

export default {
    components: {
        Editor: defineAsyncComponent(() => import('@/Shared/Editor.vue')),
        Recorder: defineAsyncComponent(() => import('@/Shared/Recorder.vue')),
        AudioRecording: defineAsyncComponent(() => import('@/Shared/AudioRecording.vue')),
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        post: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            addCommentForm: {
                content: '',
                audios: [],
				hashtags: [],
				mentions: [],
            },
            toast: null,
            recordedAudios: [],
            newComment: {}
        }
    },
    mounted() {
        this.toast = useToast();
    },
    methods: {
        deleteAudio(audio) {
            this.recordedAudios = this.recordedAudios.filter(a => a !== audio);
        },
        onAudioRecorded(audioData) {
            this.recordedAudios.push(audioData);
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        bytesToSize(bytes) {
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes === 0) return '0 Byte';
            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
        onEditorInput(output) {
            this.addCommentForm.content = output.content;
			this.addCommentForm.hashtags = output.hashtags;
			this.addCommentForm.mentions = output.mentions;
        },
        convertAudioToBlob(audio) {
            return new Blob([audio], {type: 'audio/mpeg'});
        },
        addNewComment() {
            if (this.addCommentForm.content.length === 0) {
                this.toast.error('You\'ve not entered a message?!');
                return;
            }

            const formData = new FormData();
            formData.append('content', this.addCommentForm.content);

			// Append hashtags and mentions as arrays in FormData
			this.addCommentForm.hashtags.forEach((hashtag, index) => {
				formData.append(`hashtags[${index}]`, hashtag);
			});

			this.addCommentForm.mentions.forEach((mention, index) => {
				formData.append(`mentions[${index}]`, mention);
			});

            // Convert and append audio files
            this.recordedAudios.forEach(audio => {
                const audioBlob = this.convertAudioToBlob(audio);
                formData.append('audios[]', audioBlob);
            });

            axios.post(`/post/${this.post._id}/comment`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    this.addCommentForm.content = '';
                    this.addCommentForm.audios = [];
                    this.recordedAudios = [];

                    this.$refs.editor.resetEditor();

                    this.toast.success('Comment added');

                    const post = this.$store.state.posts.find(post => post._id === this.post._id);

                    post.comments.data.push(response.data.comment);
					post.comment_count = response.data.comment_count;
				})
                .catch(error => {
                    this.toast.error('An error occurred while adding the post');
					console.log(error);
                });
        },
    },
};
</script>
