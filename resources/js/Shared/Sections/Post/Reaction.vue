<template>
    <div class="reation_area mt_12">
        <div class="reation_top d-flex align-items-center">
            <div class="react mr_8" v-for="reaction in reactions" :key="reaction.id" style="position: relative;">
                <div class="reaction-emoji">
                    {{ reaction.emoji }}
                </div>
                <div class="reactors reactor_img_22" :class="{ many_reactions: reaction.users.length > 1 }">
                    <!-- Display the first 3 users -->
                    <img v-for="(user, index) in reaction.users.slice(0, 3)" :key="user.id" :src="user.avatar" :alt="user.name" class="reactor-avatar">

                    <!-- If there are more than 3 users, show a '+X' indicator -->
                    <span class="more-users">
                        {{ reaction.users.length }}
                    </span>
                </div>
            </div>

            <!-- Button to open Emoji Picker -->
            <div class="add-emoji-button" style="position: relative;">
                <button @click="toggleEmojeeSelection" ref="emojiButton" class="emoji-button">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <teleport to="body">
            <div v-if="showEmojiSelector" class="emoji-picker-container" :style="pickerStyles" ref="emojiPicker">
                <EmojiPicker :native="true" @select="onSelectEmoji" />
            </div>
        </teleport>
    </div>
</template>

<script>
import EmojiPicker from 'vue3-emoji-picker';

export default {
    components: {
        EmojiPicker
    },
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    mounted() {
        console.log(this.user)
    },
    data() {
        return {
            showEmojiSelector: false,
            pickerStyles: {
                top: '0px',
                left: '0px',
                right: 'auto',
            },
            selectedReaction: null,
            popupStyles: {
                top: '0px',
                left: '0px',
            },
            reactions: [
                {
                    emoji: '😡',
                    users: []
                }
            ]
        };
    },
    methods: {
        toggleEmojeeSelection() {
            this.showEmojiSelector = !this.showEmojiSelector;

            if (this.showEmojiSelector) {
                this.setPosition();

                this.$nextTick(() => {
                    document.addEventListener('click', this.handleOutsideClick);
                });
            } else {
                document.removeEventListener('click', this.handleOutsideClick);
            }
        },
        setPosition() {
            const buttonRect = this.$refs.emojiButton.getBoundingClientRect();
            const pickerWidth = 300;
            const viewportWidth = window.innerWidth;

            let leftPosition = buttonRect.left + window.scrollX;
            let topPosition = buttonRect.bottom + window.scrollY;

            if (leftPosition + pickerWidth > viewportWidth) {
                leftPosition = viewportWidth - pickerWidth - 10;
            }

            this.pickerStyles.top = `${topPosition}px`;
            this.pickerStyles.left = `${leftPosition}px`;
            this.pickerStyles.right = 'auto';
        },
        handleOutsideClick(event) {
            const picker = this.$refs.emojiPicker;
            const emojiButton = this.$refs.emojiButton;

            if (
                picker && !picker.contains(event.target) &&
                emojiButton && !emojiButton.contains(event.target)
            ) {
                this.closeEmojiPicker();
            }
        },
        closeEmojiPicker() {
            this.showEmojiSelector = false;
            document.removeEventListener('click', this.handleOutsideClick);
        },
        onSelectEmoji(emoji) {
            console.log('Selected Emoji:', emoji);

            const reaction = {
                emoji: emoji.i,
                users: [
                    {
                        id: this.user.id,
                        name: this.user.username,
                        avatar: this.user.profile_picture
                    }
                ]
            }

            this.reactions.push(reaction);
            this.showEmojiSelector = false;
        }
    }
}
</script>

<style scoped>

.more-users {
    margin-left: 8px;
    color: #555;
    font-weight: bold;
}

.reaction-popup {
    position: absolute;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 10px;
    z-index: 1000;
    max-height: 150px;
    overflow-y: auto;
    width: 200px;
    border-radius: 8px;
}

.reaction-popup ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.reaction-popup li {
    padding: 5px 0;
}

.reaction-popup a.reactor-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: inherit;
    padding: 5px 0;
    transition: background-color 0.2s ease;
}

.reaction-popup a.reactor-link:hover {
    background-color: #f0f0f0;
}

.reaction-popup img.user-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    margin-right: 8px;
}

.emoji-button {
    background-color: transparent;
    border: none;
    cursor: pointer;
    font-size: 24px;
    color: #555;
    padding: 0;
}

.emoji-button:hover {
    color: #000;
}

.emoji-picker-container {
    position: absolute;
    z-index: 1000;
    background-color: white;
    border: 1px solid #ccc;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>
