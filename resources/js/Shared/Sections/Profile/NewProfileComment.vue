<template>
    <div class="quick-post-top pt-4">
        <a href="#" class="profile-img profile-img_48">
            <img :src="user.profile_picture" :alt="user.first_name + '\'s profile picture'">
        </a>
        <div class="quick-post-text position-relative">
            <div class="input-group ps-4">
                <Editor ref="editor" @editor-input="onEditorInput" />

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
import Editor from "@/Shared/Editor.vue";
import Recorder from "@/Shared/Recorder.vue";
import AudioRecording from "@/Shared/AudioRecording.vue";
import axios from "axios";

export default {
    components: {
        Editor,
        Recorder,
        AudioRecording
    },
    props: {
        user: {
            type: Object,
            required: true
        },
		username: {
			type: String,
			required: true
		}
    },
    data() {
        return {
            addCommentForm: {
                content: '',
                audios: [],
				hashtags: [],
				mentions: []
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

            this.recordedAudios.forEach(audio => {
                const audioBlob = this.convertAudioToBlob(audio);
                formData.append('audios[]', audioBlob);
            });

            axios.post(`/u/${this.username}/comments`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    this.addCommentForm.content = '';
                    this.addCommentForm.audios = [];
					this.addCommentForm.hashtags = [];
					this.addCommentForm.mentions = [];

                    this.recordedAudios = [];

                    this.$refs.editor.resetEditor();

                    this.toast.success('Comment added');

					this.$emit('comment-added');
				})
                .catch(error => {
                    this.toast.error('An error occurred while adding the comment');
					console.log(error);
                });
        },
    },
};
</script>
