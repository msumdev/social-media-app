<template>
    <div class="p-4 min-w-full">
<!--        {{ form }}-->
        <div class="relative">
            <div
                :class="['relative flex flex-col border border-black/10 dark:border-white/10 rounded-xl bg-white dark:bg-black/50 transition-colors', { 'focused': isFocused }]"
            >
                <div class="overflow-y-auto">
                    <div
                        :id="id"
                        class="ql-container ql-bubble bg-transparent border-none text-black dark:text-white px-4 py-3 resize-none focus:outline-none"
                        ref="editor"
                    ></div>
                </div>
                <div class="h-14">
                    <div
                        class="absolute left-3 right-3 bottom-3 flex items-center justify-between"
                    >
                        <div class="flex items-center gap-2">
                            <button class="px-2 py-1 text-black/50 dark:text-white/50 hover:text-black dark:hover:text-white transition-colors rounded-lg border border-black/10 dark:border-white/10 hover:border-black/20 dark:hover:border-white/20 cursor-pointer" aria-label="Attach file" type="button" @click="triggerFileInput">
                                <input id="file-input" class="hidden" type="file" accept="image/png, image/jpeg" @input="addImage($event)" ref="fileInput" multiple required />
                                <i class="fa-solid fa-images text-blue-500"></i>
                            </button>
                            <button class="px-3 py-1 text-black/50 dark:text-white/50 hover:text-black dark:hover:text-white transition-colors rounded-lg border border-black/10 dark:border-white/10 hover:border-black/20 dark:hover:border-white/20 cursor-pointer" aria-label="Attach file" type="button" @click="toggleAudioRecording">
                                <recorder @audio-recorded="onAudioRecorded" ref="audio-recorder"></recorder>
                            </button>
                            <button class="px-3 py-1 text-black/50 dark:text-white/50 hover:text-black dark:hover:text-white transition-colors rounded-lg border border-black/10 dark:border-white/10 hover:border-black/20 dark:hover:border-white/20 cursor-pointer" aria-label="Attach file" type="button">
                                <i class="fa-solid fa-location-dot text-green-600"></i>
                            </button>
                        </div>

                        <button
                            class="p-2 transition-colors text-blue-500 hover:text-blue-600 cursor-pointer"
                            aria-label="Send message"
                            type="button"
                            @click="formSubmitted"
                        >
                            <svg
                                class="w-6 h-6"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                                stroke-width="2"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <circle r="10" cy="12" cx="12"></circle>
                                <path d="m16 12-4-4-4 4"></path>
                                <path d="M12 16V8"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Quill from 'quill';
import 'quill/dist/quill.bubble.css';
import { ref, markRaw, computed } from "vue";
import Recorder from '@/Components/Utilities/Recorder.vue';

const SpanBlot = Quill.import('blots/inline');

class CustomBlot extends SpanBlot {
    static create(value) {
        let node = super.create();
        node.setAttribute('class', value);
        return node;
    }

    static formats(node) {
        return node.getAttribute('class');
    }
}

CustomBlot.blotName = 'customSpan';
CustomBlot.tagName = 'span';
Quill.register(CustomBlot);

export default {
    name: 'QuillEditor',
    components: {
        Recorder
    },
    props: {
        id: {
            type: String,
            default: 'editor',
        },
        modelValue: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            quill: null,
            isFocused: false,
        };
    },
    computed: {
        form: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    },
    mounted() {
        const container = document.getElementById(this.id);

        const quill = markRaw(new Quill(container, {
            theme: 'bubble',
            modules: {
                toolbar: false,
            },
            placeholder: 'Type your message here...',
        }));

        this.quill = quill;

        if (this.form?.html) {
            this.quill.root.innerHTML = this.form.html;
        }

        quill.on('text-change', (delta, oldDelta, source) => {
            if (source === 'user') {
                const text = quill.getText();
                const html = quill.root.innerHTML;
                const hashtags = text.match(/#\w+/g)?.map(word => word.substring(1)) || [];
                const mentions = text.match(/@\w+/g)?.map(word => word.substring(1)) || [];

                let currentPosition = 0;
                const words = text.split(/(\s+)/);

                words.forEach((word) => {
                    const index = text.indexOf(word, currentPosition);

                    if (word.startsWith('#')) {
                        quill.formatText(index, word.length, 'customSpan', 'bg-blue-50 text-blue-400 me-1 px-1.5 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300');
                    } else if (word.startsWith('@')) {
                        quill.formatText(index, word.length, 'customSpan', 'bg-green-50 text-green-600 me-1 px-1.5 py-0.5 rounded-sm dark:bg-green-900 dark:text-green-300');
                    } else {
                        quill.formatText(index, word.length, 'customSpan', false);
                    }

                    currentPosition += word.length;
                });

                this.form = {
                    ...this.form,
                    text,
                    html,
                    hashtags,
                    mentions,
                };
            }
        });

        this.$refs.editor.addEventListener('focusin', () => this.isFocused = true);
        this.$refs.editor.addEventListener('focusout', () => this.isFocused = false);
    },
    methods: {
        resetEditor() {
            this.quill.setText('');
        },
        setContent(content) {
            this.quill.setContents(content);
        },
        addContent(content) {
            const selection = this.quill.getSelection(true);

            if (selection) {
                this.quill.insertText(selection.index, content);
            } else {
                this.quill.insertText(this.quill.getLength(), content);
            }
        },
        onAudioRecorded(src) {
            this.form = {
                ...this.form,
                audio: [...this.form.audio, src],
            };
        },
        formSubmitted() {
            this.$emit('submit', this.form);

            this.resetEditor();
        },
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        addImage(event) {
            let images = [];

            if (event.dataTransfer?.files?.length) {
                images = event.dataTransfer.files;
            } else if (event.target?.files?.length) {
                images = event.target.files;
            }

            if (images.length) {
                Array.from(images).forEach((image) => {
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        const newImage = {
                            name: image.name,
                            size: this.bytesToSize(image.size),
                            data: e.target.result,
                            file: image
                        };

                        this.form = {
                            ...this.form,
                            images: [...this.form.images, newImage],
                        };
                    };

                    reader.readAsDataURL(image);
                });
            }
        },
        bytesToSize(bytes) {
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes === 0) return '0 Byte';
            const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
        toggleAudioRecording() {
            const recorder = this.$refs['audio-recorder'];
            recorder.toggle();
        },
    },
};
</script>


<style>
.ql-editor::before {
    font-style: normal !important;
}

.ql-editor {
    white-space: normal !important;
    word-break: break-word;
    overflow-wrap: break-word;
    color: inherit;
    background-color: transparent;
    padding: 0;
    font-size: 1rem;
    line-height: 1.5;
}

.dark .ql-editor::before {
    color: oklch(55.1% 0.027 264.364);
}

.focused {
    box-shadow: 0 0 5px rgba(0, 153, 255, 0.6);
}

.dark .focused {
    box-shadow: 0 0 5px rgba(0, 255, 204, 0.4);
}

</style>
