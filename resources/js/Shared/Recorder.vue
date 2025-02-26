<template>
    <div>
        <button type="button" class="site-icon border_left fs-2" @click="startRecording" v-if="!isRecording">
            <i class="bi bi-mic"></i>
        </button>
        <button type="button" class="site-icon border_left fs-2" @click="stopRecording" v-if="isRecording">
            <i class="bi bi-mic"></i>
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isRecording: false,
            mediaRecorder: null,
            audioChunks: [],
            audioBlob: null,
            stream: null, // Add this to keep track of the media stream
        };
    },
    methods: {
        async startRecording() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.mediaRecorder = new MediaRecorder(this.stream);
                this.audioChunks = [];
                this.isRecording = true;

                // Push audio data as it's recorded
                this.mediaRecorder.ondataavailable = (event) => {
                    this.audioChunks.push(event.data);
                };

                // When the recording stops, create the blob and stop the stream
                this.mediaRecorder.onstop = () => {
                    // Create Blob from audio chunks
                    this.audioBlob = new Blob(this.audioChunks, { type: 'audio/mpeg' });

                    // Convert the Blob to Base64 using FileReader
                    const reader = new FileReader();
                    reader.readAsDataURL(this.audioBlob);
                    reader.onloadend = () => {
                        const base64data = reader.result;
                        // Emit the Base64-encoded string
                        this.$emit('audio-recorded', base64data);
                    };

                    // Stop the audio stream
                    this.stopMediaStream();
                };

                // Start recording
                this.mediaRecorder.start();
            } catch (error) {
                console.error('Error accessing microphone', error);
            }
        },
        stopRecording() {
            if (this.mediaRecorder && this.isRecording) {
                this.mediaRecorder.stop(); // This triggers the onstop event to create the blob
                this.isRecording = false;
            }
        },
        stopMediaStream() {
            if (this.stream) {
                // Stop all tracks to release the microphone
                this.stream.getTracks().forEach((track) => track.stop());
                this.stream = null; // Clean up the stream reference
            }
        },
        clearRecording() {
            if (this.mediaRecorder && this.isRecording) {
                this.mediaRecorder.stop(); // Stop the recorder if it's still active
                this.isRecording = false;
            }

            this.audioBlob = null;
            this.audioChunks = [];
        },
    },
};
</script>
