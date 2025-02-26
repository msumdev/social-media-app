<template>
    <div id="preloader" v-if="isLoading">
        <div class="loader3">
            <span></span>
            <span></span>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isLoading: true
        }
    },
    mounted() {
        if (document.readyState === 'complete') {
            this.hidePreloader();
        } else {
            window.addEventListener('load', this.hidePreloader);
        }
    },
    beforeDestroy() {
        window.removeEventListener('load', this.hidePreloader);
    },
    methods: {
        hidePreloader() {
            setTimeout(() => {
                this.isLoading = false
            }, 400);
        }
    }
}
</script>
