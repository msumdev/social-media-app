<template>
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <img :src="image.data">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-danger" data-bs-dismiss="modal" @click="deleteImage">Delete</button>
                    <button type="button" class="btn btn-lg btn-secondary" data-bs-dismiss="modal" @click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        image: {
            type: Object,
            required: true
        }
    },
    methods: {
        deleteImage() {
            this.$emit('deleteImage', this.image);
        },
        closeModal() {
            this.$emit('closeImageModal');
        }
    }
};
</script>
