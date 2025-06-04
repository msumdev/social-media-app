<template>
    <div class="relative">
        <button
            ref="triggerRef"
            @click="toggle"
            class="flex items-center justify-center px-2 py-1 text-sm text-gray-800 rounded-full hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                 stroke-width="1.5" stroke="currentColor"
                 class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15.182 15.182a4.5 4.5 0 0 1-6.364 0M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
            </svg>
        </button>


        <teleport to="body">
            <div v-if="isVisible" ref="pickerRef" class="z-[100]">
                <emoji-picker :native="true" @select="onReaction" />
            </div>
        </teleport>
    </div>
</template>

<script>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { createPopper } from '@popperjs/core';
import EmojiPicker from 'vue3-emoji-picker';
import 'vue3-emoji-picker/css';

export default {
    name: 'EmojiTrigger',
    components: { EmojiPicker },
    setup(props, context) {
        const isVisible = ref(false);
        const triggerRef = ref(null);
        const pickerRef = ref(null);
        let popperInstance = null;

        const toggle = async () => {
            isVisible.value = !isVisible.value;
            await nextTick();
            if (isVisible.value && triggerRef.value && pickerRef.value) {
                popperInstance?.destroy();
                popperInstance = createPopper(triggerRef.value, pickerRef.value, {
                    placement: 'top-start',
                    modifiers: [{ name: 'offset', options: { offset: [0, 8] } }],
                });
            }
        };

        const handleClickOutside = (event) => {
            if (
                pickerRef.value &&
                !pickerRef.value.contains(event.target) &&
                triggerRef.value &&
                !triggerRef.value.contains(event.target)
            ) {
                isVisible.value = false;
            }
        };

        onMounted(() => {
            document.addEventListener('pointerdown', handleClickOutside);
        });

        onBeforeUnmount(() => {
            document.removeEventListener('pointerdown', handleClickOutside);
            popperInstance?.destroy();
        });

        const onReaction = (emoji) => {
            context.emit('emoji-added', emoji);

            isVisible.value = false;
        };

        return {
            isVisible,
            triggerRef,
            pickerRef,
            toggle,
            onReaction,
        };
    },
};
</script>
