<template>
    <i class="fa-solid fa-stop text-red-500" v-if="isRecording"></i>
    <i class="fa-solid fa-microphone text-blue-500" v-if="!isRecording"></i>
</template>

<script>
export default {
    emits: ['audio-recorded'],
    data() {
        return {
            isRecording: false,
            mediaRecorder: null,
            chunks: [],
            blob: null,
            stream: null,
        };
    },
    methods: {
        async toggle() {
            if (this.isRecording) {
                this.mediaRecorder.stop();

                this.isRecording = false
            } else {
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.mediaRecorder = new MediaRecorder(this.stream);

                    this.chunks = [];

                    this.mediaRecorder.ondataavailable = (event) => {
                        this.chunks.push(event.data);
                    };

                    this.mediaRecorder.onstop = () => {
                        this.blob = new Blob(this.chunks, { type: 'audio/ogg; codecs=opus' });

                        const url = window.URL.createObjectURL(this.blob);
                        this.$emit('audio-recorded', url);

                        this.stopMediaStream();
                        this.mediaRecorder.stop();
                    };

                    this.mediaRecorder.start();

                    this.isRecording = true;
                } catch (error) {
                    console.error('Error accessing microphone', error);
                }
            }
        },
        stopMediaStream() {
            if (this.stream) {
                this.stream.getTracks().forEach((track) => track.stop());
                this.stream = null;
            }
        },
        clearRecording() {
            if (this.mediaRecorder && this.isRecording) {
                this.mediaRecorder.stop();
            }

            this.blob = null;
            this.chunks = [];
        },
    },
};
</script>
