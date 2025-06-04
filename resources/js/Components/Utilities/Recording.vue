<template>
    <div :class="class">
        <div class="w-full bg-gray-100 shadow-md rounded-lg px-4 py-3 flex items-center justify-between dark:bg-gray-700">
            <div class="flex items-center gap-4 flex-1">
                <audio ref="audioElement" preload="metadata"></audio>
                <div class="flex items-center gap-2 flex-1">
                    <span id="current-time" class="text-sm text-gray-600 w-10 text-right dark:text-gray-400">{{ currentTimeFormatted }}</span>

                    <input type="range" id="seek-slider" max="100" v-model="seekValue" @input="updateCurrentTime" @change="seekAudio" :style="sliderStyle" class="w-full h-1 bg-gray-300 rounded-lg appearance-none cursor-pointer [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-3 [&::-webkit-slider-thumb]:h-3 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-blue-500" />

                    <span id="duration" class="text-sm text-gray-600 w-10 dark:text-gray-400">{{ durationFormatted }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3 ml-4">
                <button type="button" class="text-gray-700 hover:text-blue-600 transition cursor-pointer dark:text-gray-500" @click="togglePlayPause" v-if="playState === 'play'">
                    <i class="fa-solid fa-play text-lg"></i>
                </button>
                <button type="button" class="text-gray-700 hover:text-blue-600 transition cursor-pointer dark:text-gray-500" @click="togglePlayPause" v-if="playState === 'pause'">
                    <i class="fa-solid fa-pause text-lg"></i>
                </button>
                <button type="button" class="text-red-500 hover:text-red-600 transition cursor-pointer dark:text-red-900" @click="removeAudio">
                    <i class="fa-solid fa-trash text-lg"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AudioPlayer',
    props: {
        src: {
            type: String,
            required: false,
        },
        class: {
            type: String,
            required: false,
        },
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
        this.loadAudio(this.src);
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
        removeAudio() {
            this.$emit('remove-audio');
        },
    },
};
</script>

<style scoped>
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
