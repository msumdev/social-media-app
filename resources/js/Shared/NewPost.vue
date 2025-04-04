<template>
    <div class="section-wrapper quick_post mb_20" @dragover.prevent @dragenter.prevent @dragstart.prevent @drop.prevent="addImage($event)">
        <div class="section-top pb_32">
            <h2 class="pb-3 title16">Quick post</h2>
        </div>

        <div class="quick_post_content">
            <div class="quick-post-top">
                <Link :href="'/u/' + user.username" class="profile-img profile-img_48">
                    <img :src="user.profile_picture" alt="admin_img">
                </Link>
                <div class="input-group ps-4">
                    <Editor ref="editor" @editor-input="onEditorInput" @emoji-added="onEmojiAdded" />

                    <span class="input-group-text" :class="$store.state.isNightMode ? 'bg-dark' : 'bg-light'">
                        <Recorder @audio-recorded="onAudioRecorded" />
                    </span>
                    <span class="input-group-text" :class="$store.state.isNightMode ? 'bg-dark' : 'bg-light'">
                        <button type="button" class="site-icon fs-2" @click="toggleEmojiPicker">
                            <i class="bi bi-emoji-smile"></i>
                        </button>
                        <div v-if="isEmojiPickerVisible" class="emoji-picker-container" ref="emojiPicker">
                            <EmojiPicker :native="true" @select="onSelectEmoji" />
                        </div>
                    </span>
                    <span class="input-group-text" :class="$store.state.isNightMode ? 'bg-dark' : 'bg-light'">
                        <input id="file-input" type="file" accept="image/png, image/jpeg" @input="addImage($event)" ref="fileInput" style="display: none;" multiple required />

                        <button type="button" class="site-icon fs-2" @click="triggerFileInput">
                            <i class="bi bi-paperclip"></i>
                        </button>
                    </span>
                </div>
            </div>

            <!-- Upload area for images or audios -->
            <div class="upload-area" v-if="addPostForm.images.length > 0 || recordedAudios.length > 0">
                <div class="upload-content img_upload d-flex align-items-center justify-content-between" v-for="image in addPostForm.images" :key="image.name">
                    <div class="img_upload_left d-flex align-items-center gap_x_8" @click="viewImage(image)">
                        <div class="img-upload-icon p_8">
                            <img :src="image.data" class="icon16">
                        </div>
                        <div class="img-upload-progress">
                            <p class="pb_5 text-sm text-dark-grey">{{ image.name }}</p>
                            <div class="custom-progress-bar">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="img_upload_right d-flex align-items-center gap_x_6">
                        <p class="text-sm text-dark-grey">{{ image.size }}</p>
                        <button type="button" class="site-icon" @click="removeImage(image)">
                            <img src="/images/Close_2.svg" class="icon16" alt="Close_2">
                        </button>
                    </div>
                </div>

                <AudioRecording v-for="audio in recordedAudios" v-if="recordedAudios.length > 0" :key="audio" :audio-src="audio" @delete-audio="deleteAudio(audio)" />
            </div>

            <!-- Post button -->
            <div class="d-flex justify-content-end mt-3">
                <div class="spinner-border me-4" role="status" v-if="addingNewPost">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <button type="button" class="btn btn-main btn-lg" @click="addPost" v-else>Post</button>
            </div>
        </div>
    </div>

    <ImageModal :image="this.selectedImage" :showImageModal="showImageModal" v-if="selectedImage" @closeImageModal="closeImageModal" @deleteImage="removeImage" />
</template>

<style scoped>
.emoji-picker-container {
    position: absolute;
    top: 40px; /* Adjust to position below the button */
    right: 0;
    z-index: 10;
    background: var(--light-bg, #fff); /* Match your light/dark theme */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}
</style>

<script>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import {useToast} from "vue-toastification";
import Editor from "@/Shared/Editor.vue";
import AudioRecording from "@/Shared/AudioRecording.vue";
import Recorder from "@/Shared/Recorder.vue";
import ImageModal from "@/Shared/ImageModal.vue";
import 'vue3-emoji-picker/css'
import EmojiPicker from 'vue3-emoji-picker'
import {Link} from "@inertiajs/vue3";
import axios from "axios";

export default {
    components: {
        Editor,
        Recorder,
        AudioRecording,
        ImageModal,
        EmojiPicker,
		Link
    },
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            addPostForm: {
                content: '',
                hashtags: [],
                mentions: [],
                images: [],
                audios: [],
            },
            addingNewPost: false,
            toast: null,
            recordedAudios: [],
            selectedImage: null,
            showImageModal: false,
            newPost: {},
            isEmojiPickerVisible: false
        }
    },
    setup() {
        const emojiPicker = ref(null); // Reference to the emoji picker
        const isEmojiPickerVisible = ref(false);

        const handleOutsideClick = (event) => {
            if (
                emojiPicker.value &&
                !emojiPicker.value.contains(event.target) &&
                !event.target.closest('.bi-emoji-smile')
            ) {
                isEmojiPickerVisible.value = false;
            }
        };

        onMounted(() => {
            document.addEventListener('click', handleOutsideClick);
        });

        onBeforeUnmount(() => {
            document.removeEventListener('click', handleOutsideClick);
        });

        return {
            emojiPicker,
            isEmojiPickerVisible,
            handleOutsideClick,
        };
    },
    mounted() {
        this.toast = useToast();
    },
    methods: {
        addImage(event) {
            let images = [];

            if (event.dataTransfer && event.dataTransfer.files.length > 0) {
                images = event.dataTransfer.files;
            } else if (event.target && event.target.files.length > 0) {
                images = event.target.files;
            }

            if (images.length > 0) {
                Array.from(images).forEach((image) => {
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        this.addPostForm.images.push({
                            name: image.name,
                            size: this.bytesToSize(image.size),
                            data: e.target.result,
                            file: image
                        });
                    };

                    reader.readAsDataURL(image);
                });
            }
        },
        deleteAudio(audio) {
            this.recordedAudios = this.recordedAudios.filter(a => a !== audio);
        },
        onAudioRecorded(audioData) {
            this.recordedAudios.push(audioData);
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        removeImage(image) {
            this.addPostForm.images = this.addPostForm.images.filter(file => file !== image);
        },
        bytesToSize(bytes) {
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes === 0) return '0 Byte';
            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
        viewImage(image) {
            this.selectedImage = image;

            this.$nextTick(() => {
                const modalElement = document.getElementById('imageModal');
                if (!modalElement) {
                    return;
                }

                const modal = new bootstrap.Modal(modalElement, {
                    keyboard: false,
                    backdrop: 'static'
                });

                modal.show();
            });
        },
        closeImageModal() {
            this.selectedImage = null;
        },
        onEditorInput(output) {
            this.addPostForm.content = output.content;
            this.addPostForm.hashtags = output.hashtags;
            this.addPostForm.mentions = output.mentions;
        },
        convertAudioToBlob(audio) {
            return new Blob([audio], {type: 'audio/mpeg'});
        },
        addPost() {
            if (this.addPostForm.content.length === 0) {
                this.toast.error('You\'ve not entered a message?!');
                return;
            }

            const formData = new FormData();
            formData.append('content', this.addPostForm.content);

            // Append hashtags and mentions as arrays in FormData
            this.addPostForm.hashtags.forEach((hashtag, index) => {
                formData.append(`hashtags[${index}]`, hashtag);
            });
            this.addPostForm.mentions.forEach((mention, index) => {
                formData.append(`mentions[${index}]`, mention);
            });

            // Append images as files
            this.addPostForm.images.forEach(image => {
                formData.append('images[]', image.file);
            });

            // Convert and append audio files
            this.recordedAudios.forEach(audio => {
                const audioBlob = this.convertAudioToBlob(audio);
                formData.append('audios[]', audioBlob);
            });

            this.addingNewPost = true;

            axios.post('/post', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {
                    // Reset form fields on success
                    this.addPostForm.content = '';
                    this.addPostForm.hashtags = [];
                    this.addPostForm.mentions = [];
                    this.addPostForm.audios = [];
                    this.addPostForm.images = [];
                    this.recordedAudios = [];

                    this.$refs.editor.resetEditor();

                    // Success notification
                    this.toast.success('Post added successfully');
                    this.addingNewPost = false;

                    console.log(response.data);
                    this.$store.state.posts.unshift(response.data);
                })
                .catch(error => {
                    console.error(error);

                    this.addingNewPost = false;
                    this.toast.error('An error occurred while adding the post');
                });
        },
        toggleEmojiPicker() {
            this.isEmojiPickerVisible = !this.isEmojiPickerVisible;
        },
        onSelectEmoji(emoji) {
            this.$refs.editor.addContent(emoji.i);
            // this.isEmojiPickerVisible = false;
        },
        onEmojiAdded(content) {
            this.addPostForm.content = content;
        }
    },
};
</script>
