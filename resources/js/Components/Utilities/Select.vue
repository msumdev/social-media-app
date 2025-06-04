<template>
    <VueSelect
        class="cursor-pointer"
        :options="options"
        @option-selected="onChange"
        :shouldAutofocusOption="false"
        :get-option-value="option => option.id"
    ></VueSelect>
</template>

<script>
import { DarkModeStore } from '@/Stores/DarkModeStore';
import VueSelect from "vue3-select-component";

export default {
    name: "Select",
    components: {
        VueSelect
    },
    props: {
        options: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            darkModeStore: DarkModeStore(),
            placement: 'bottom',
            selected: null,
        }
    },
    methods: {
        onChange(selected) {
            this.selected = selected;

            this.$emit('changed', selected);
        },
    }
}
</script>

<style>
.vue-select {
    --vs-font-size: 14px;
    --vs-font-weight: 300;
    --vs-font-family: inherit;
    --vs-option-font-size: 14px;
}

body:not(.dark) .vue-select {
    --vs-background-color: oklch(0.985 0.002 247.839);
    --vs-option-background-color: oklch(0.985 0.002 247.839);
    --vs-multi-value-background-color: oklch(0.872 0.01 258.338)
}

body.dark .vue-select {
    --vs-background-color: oklch(0.373 0.034 259.733);
    --vs-text-color: oklch(0.21 0.034 264.665);
    --vs-placeholder-color: #9ca3af;
    --vs-indicator-icon-color: #9ca3af;
    --vs-border: 1px solid oklch(0.446 0.03 256.802);
    --vs-option-hover-background-color: #9ca3af;
    --vs-menu-background-color: oklch(0.446 0.03 256.802);
    --vs-option-text-color: #9ca3af;
    --vs-menu-border: 1px solid oklch(0.446 0.03 256.802);
    --vs-option-selected-background-color: oklch(0.278 0.033 256.848);
    --vs-option-selected-text-color: #9ca3af;
    --vs-multi-value-background-color: oklch(0.278 0.033 256.848);
    --vs-multi-value-label-text-color: #9ca3af;
    --vs-multi-value-xmark-color: #9ca3af;
}

.dark .vue-select .single-value,
.dark .vue-select .search-input,
.dark .vue-select .no-results
{
    color: #9ca3af;
}

.input-placeholder {
    font-size: 14px;
}
</style>
