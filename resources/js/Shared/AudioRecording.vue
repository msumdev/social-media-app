<template>
    <div class="upload-content audio_upload d-flex align-items-center justify-content-between" :class="containerClasses ? containerClasses : ''">
        <div class="audio_upload_left d-flex align-items-center gap_x_8">
            <audio ref="audioElement" preload="metadata"></audio>
            <div class="audio_slider_wrapper d-flex align-items-center">
                <span id="current-time" class="time">{{ currentTimeFormatted }}</span>
                <input type="range" id="seek-slider" max="100" v-model="seekValue" @input="updateCurrentTime" @change="seekAudio" :style="sliderStyle">
                <span id="duration" class="time">{{ durationFormatted }}</span>
            </div>
        </div>
        <div class="audio_upload_right d-flex align-items-center gap_x_6">
            <button type="button" class="site-icon" id="play-icon" @click="togglePlayPause" v-if="playState === 'play'">
                <img src="/images/play-button.svg" class="icon16" alt="play">
            </button>
            <button type="button" class="site-icon" id="play-icon" @click="togglePlayPause" v-if="playState === 'pause'">
                <img src="/images/pause-button.svg" class="icon16" alt="pause">
            </button>
            <button type="button" class="site-icon" v-if="!isEmbedded">
                <img src="/images/Close_2.svg" class="icon16" alt="close" @click="deleteAudio">
            </button>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'AudioPlayer',
    props: {
        audioSrc: {
            type: String,
            required: true,
        },
		path: {
			type: String,
			required: true
		},
		isEmbedded: {
			type: Boolean,
			default: false
		},
		containerClasses: {
			type: String,
			default: ''
		}
    },
    data() {
        return {
            playState: 'play',
            seekValue: 0,
            volume: 100,
            duration: 0,
            currentTime: 0,
        };
    },
    computed: {
        currentTimeFormatted() {
            return this.formatTime(this.currentTime);
        },
        durationFormatted() {
            return this.formatTime(this.duration);
        },
        sliderStyle() {
            const percentagePlayed = (this.seekValue / 100) * 100;
            return `background: linear-gradient(to right, #4182EB ${percentagePlayed}%, #ddd ${percentagePlayed}%);`;
        }
    },
    mounted() {
		if (this.path) {
			this.loadAudioFromPath(this.path);
		}

        if (this.audioSrc) {
            this.loadAudio(this.audioSrc);
        }
    },
    methods: {
        loadAudio(src) {
            const audio = this.$refs.audioElement;
            audio.src = src;

            audio.addEventListener('loadedmetadata', () => {
                if (audio.duration === Infinity || isNaN(Number(audio.duration))) {
                    audio.currentTime = 1e101
                    audio.addEventListener('timeupdate', this.getDuration)
                }
            })

            audio.addEventListener('timeupdate', () => {
                if (!isNaN(this.duration) && this.duration > 0) {
                    this.currentTime = audio.currentTime;
                    this.seekValue = (audio.currentTime / this.duration) * 100;

					if (audio.currentTime === audio.duration) {
						this.playState = 'play';
					}
                }
            });
        },
		loadAudioFromPath(path) {
			axios.get(this.path)
				.then(response => {
					this.loadAudio(response.data);
				})
				.catch(error => {
					console.error(error);
				});
		},
        togglePlayPause() {
            const audio = this.$refs.audioElement;
            if (this.playState === 'play') {
                audio.play();
                this.playState = 'pause';
            } else {
                audio.pause();
                this.playState = 'play';
            }
        },
        getDuration(event) {
            event.target.currentTime = 0
            event.target.removeEventListener('timeupdate', this.getDuration)
            this.duration = event.target.duration;
        },
        updateCurrentTime() {
            const audio = this.$refs.audioElement;
            this.currentTime = (this.seekValue / 100) * this.duration;
            audio.currentTime = this.currentTime;
        },
        seekAudio() {
            const audio = this.$refs.audioElement;
            audio.currentTime = (this.seekValue / 100) * this.duration;
        },
        formatTime(seconds) {
            if (!seconds || isNaN(seconds)) return "0:00"; // Handle cases where time is not available
            const minutes = Math.floor(seconds / 60);
            const sec = Math.floor(seconds % 60);
            return `${minutes}:${sec < 10 ? '0' : ''}${sec}`;
        },
        deleteAudio() {
            this.$emit('delete-audio');
        },
    },
};
</script>

<style scoped>
.upload-content {
    width: 100%;
}

.audio_upload_left {
    width: 100%;
}

.audio_slider_wrapper {
    display: flex;
    align-items: center;
    width: 100%;
}

#current-time, #duration {
    min-width: 50px;
    text-align: center;
    font-size: 14px;
}

#seek-slider {
    flex-grow: 1;
    margin: 0 10px;
    appearance: none;
    height: 5px;
    background: #ddd;
    border-radius: 5px;
    outline: none;
    position: relative;
}

#seek-slider::-webkit-slider-runnable-track {
    width: 100%;
    height: 5px;
    cursor: pointer;
    background: transparent;
}

#seek-slider::-moz-range-track {
    width: 100%;
    height: 5px;
    cursor: pointer;
    background: transparent;
}

#seek-slider::-ms-track {
    width: 100%;
    height: 5px;
    background: transparent;
    border-color: transparent;
    color: transparent;
}

#seek-slider::-webkit-slider-thumb {
    appearance: none;
    width: 15px;
    height: 15px;
    background: #4182EB;
    border-radius: 50%;
    cursor: pointer;
    margin-top: -5px;
}

#seek-slider::-moz-range-thumb {
    width: 15px;
    height: 15px;
    background: #4182EB;
    border-radius: 50%;
    cursor: pointer;
    transform: translateY(-2px);
}

#seek-slider::-ms-thumb {
    width: 15px;
    height: 15px;
    background: #4182EB;
    border-radius: 50%;
    cursor: pointer;
    margin-top: -5px;
}
</style>
