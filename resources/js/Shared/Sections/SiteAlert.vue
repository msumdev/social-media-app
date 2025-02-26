<template>
    <div class="collapse show" id="notice_collapse">
        <div class="notice round12 bg-white mb_20">
            <div class="notice-top p_16 d-flex align-items-center justify-content-between">
                <div class="notice_left d-flex align-items-center">
                    <div class="notice-icon round100 d-flex align-items-center gap_x_12">
                        <img src="/images/info-circle.svg" class="icon16" alt="info">
                    </div>
                    <h3 class="text_md text-blue">Important Update</h3>
                </div>
                <div class="notice_right">
                    <button type="button" class="site-icon" data-bs-toggle="collapse" href="#notice_collapse" role="button"
                            aria-expanded="false" aria-controls="notice_collapse">
                        <img src="/images/Close_2.svg" class="icon16" alt="Close_2">
                    </button>
                </div>
            </div>
            <div class="notice_bottom p_16">
                <h3 class="text_md text-blue pb_6">Maintenance Notice</h3>
                <p>The site will undergo maintenance on <span class="text_black f_700">Saturday</span> from <span
                    class="text_black f_700">2 AM to 4 AM</span> GMT. Please save your work and log out before this time.
                </p>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
    },
    mounted() {
    },
    beforeDestroy() {
    },
    methods: {
    }
}
</script>
