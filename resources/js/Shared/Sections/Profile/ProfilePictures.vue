<template>
	<div class="section-wrapper">
		<div class="section-top mb-4">
			<h2 class="pb-3 title16">Picture album </h2>
		</div>
		<!-- updates_list -->
		<div class="top-friends-list-area scrollbar d-flex gap_y_12">
			<a href="#" class="album create_album">
				<div class="uploaded-image">

					<div class="create-album-icon">
						<img src="/images/plus.svg" alt="plus">
					</div>
				</div>
				<div class="album-text">
					<h4 class="text_xsm f_700">Create a New<br> Album</h4>
				</div>
			</a>
			<a href="#" class="album">
				<div class="uploaded-image">
					<img src="/images/uploaded_img1.png" alt="uploaded_img1">

					<button type="button" class="position-absolute pr_5 pt_5 round100">
						<img src="/images/more.svg" class="icon12" alt="more">
					</button>
				</div>
				<div class="album-text">
					<h4 class="text_xsm f_700 ">profile pictures</h4>
					<p class="text_xsm f_400">13 items</p>
				</div>
			</a>
		</div>
	</div>
</template>

<script>
import {useToast} from "vue-toastification";

export default {
    components: {
    },
    props: {
        pictures: {
			type: Array,
			required: true
		}
    },
	data() {
		return {
			toast: null,
		}
	},
	mounted() {
		this.toast = useToast();
	},
    computed: {

    },
	methods: {

	}
}
</script>

<style scoped>

</style>
