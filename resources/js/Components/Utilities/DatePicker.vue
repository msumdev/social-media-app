<script>
import { ref } from 'vue';
import { DarkModeStore } from '@/Stores/DarkModeStore';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

export default {
    name: "DatePicker",
    components: {
        VueDatePicker
    },
    data() {
        return {
            datePickerRef: ref(),
            darkModeStore: DarkModeStore(),
            flow: ref(['month', 'year', 'calendar'])
        }
    },
    methods: {
        onChange(date) {
            this.$emit('update:modelValue', date);
        }
    }
}
</script>

<template>
    <VueDatePicker
        :enable-time-picker="false"
        :dark="darkModeStore.isEnabled"
        @update:model-value="onChange"
        :flow="flow"
    ></VueDatePicker>
</template>

<style>
.dp__theme_dark {
    --dp-background-color: oklch(0.373 0.034 259.733);
    --dp-border-color: oklch(0.446 0.03 256.802);
    --dp-menu-border-color: oklch(0.446 0.03 256.802);
    --dp-border-color-hover: oklch(0.446 0.03 256.802);
    --dp-border-color-focus: oklch(0.623 0.214 259.815);
    --dp-font-size: 0.8rem;
}

.dp__theme_light {
    --dp-background-color: oklch(0.985 0.002 247.839);
}
</style>
