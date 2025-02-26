<template>
    <div
        class="border form-control"
        :id="id"
        :class="['ql-container ql-bubble fs-3', $store.state.isNightMode ? 'bg-dark' : 'bg-light']"
        ref="editor"
    >
    </div>
</template>

<script>
import Quill from 'quill';
import 'quill/dist/quill.bubble.css';
import { Delta } from "quill/core.js";
import {ref, markRaw} from "vue";

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
    data() {
        return {
            quill: null,
            container: null,
            toolbar: null,
            quillRef: ref(null),
            data: {
                hashtags: [],
                mentions: [],
                content: ""
            }
        };
    },
    props: {
        id: {
            type: String,
            required: true,
            default: 'editor',
        },
        disableToolbar: {
            type: Boolean,
            required: false,
            default: false,
        },
        modelValue: {
            type: String,
            required: false,
            default: '',
        },
        textOnly: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    mounted() {
        this.container = document.getElementById(this.id);

        if (this.disableToolbar) {
            this.toolbar = false;
        } else {
            this.toolbar = [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'header': [1, 2, false] }],
            ];
        }

        const quill = markRaw(new Quill(this.container, {
            theme: 'bubble',
            modules: {
                toolbar: this.toolbar,
            },
            placeholder: 'Type your message here...',
        }));

        this.quill = quill;

        // Set initial content from modelValue
        if (this.modelValue) {
            this.quill.root.innerHTML = this.modelValue;
        }

        // Add keypress event listener
        this.quill.root.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault(); // Prevent default enter behavior

                // Emit the content for v-model
                this.$emit('update:modelValue', this.data.content);

                // Emit a custom submit event
                this.$emit('submit-message', this.data);

                this.resetEditor();
            } else if (event.key === 'Enter' && event.shiftKey) {
                // Allow new line insertion on Shift + Enter
                return;
            }
        });

        quill.on('text-change', (delta, oldDelta, source) => {
            if (source === 'user') {
                let text = quill.getText();
                let currentPosition = 0;
                let words = text.split(/(\s+)/);

                words.forEach((word) => {
                    if (word.startsWith('#')) {
                        let index = text.indexOf(word, currentPosition);
                        quill.formatText(index, word.length, 'customSpan', 'badge hashtag');
                    } else if (word.startsWith('@')) {
                        let index = text.indexOf(word, currentPosition);
                        quill.formatText(index, word.length, 'customSpan', 'badge mention');
                    } else {
                        let index = text.indexOf(word, currentPosition);
                        quill.formatText(index, word.length, 'customSpan', false);
                    }

                    currentPosition += word.length;
                });

                if (this.textOnly) {
                    this.data.content = quill.getText();
                } else {
                    this.data.content = this.quill.root.innerHTML;
                }

                this.data.hashtags = quill.getText().match(/#\w+/g)?.map(word => word.substring(1)) || [];
                this.data.mentions = quill.getText().match(/@\w+/g)?.map(word => word.substring(1)) || [];

                // Emit the content for v-model
                this.$emit('update:modelValue', this.data.content);

                // Emit custom input event for existing functionality
                this.$emit('editor-input', this.data);
            }
        });
    },
    watch: {
        modelValue(newValue) {
            if (newValue !== this.quill.root.innerHTML) {
                this.quill.root.innerHTML = newValue;
            }
        }
    },
    methods: {
        resetEditor() {
            this.quill.setText('');

            this.$emit('update:modelValue', '');
        },
        setContent(content) {
            this.quill.setContent(content);

            this.$emit('update:modelValue', content);
        },
        addContent(content) {
            const selection = this.quill.getSelection(true);

            if (selection) {
                this.quill.insertText(selection.index, content);
            } else {
                this.quill.insertText(this.quill.getLength(), content);
            }

            this.$emit('emoji-added', this.quill.root.innerHTML);
        },
    },
};
</script>
