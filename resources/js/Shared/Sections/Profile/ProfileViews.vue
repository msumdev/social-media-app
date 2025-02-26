<template>
	<div class="section-wrapper mt-4">
		<div class="section-top mb-4">
			<h2 class="pb-3 title16">Recent profile views</h2>
		</div>

		<!-- updates_list -->
		<div class="updates_list d-grid gap_y_12 scrollbar overflow-x-auto" style="max-height: 200px;">
			<Link :href="'/u/' + log.profile.username" class="updates p_8" v-for="log in logs">
				<div class="profile-img profile-img_48">
					<img src="/images/Avatar11.png" alt="Avatar11">

					<!-- active dot -->
					<div class="active_dot active">
					</div>
				</div>
				<div class="update_right">
					<div class="d-flex align-items-end pb_5">
						<h2 class="text_15">{{ log.profile.username }}</h2>
					</div>
					<div class="d-flex align-items-center justify-content-between">
						<p class="text_xsm place-name">{{ log.profile.city.name }}</p>
						<p class="text-dark-grey text_xsm" :title="log.created_at_title">Viewed {{ log.created_at_display }}</p>
					</div>
				</div>
			</Link>
			<div ref="loadingDiv" class="loading-div"></div>
		</div>
	</div>
</template>

<script>
import axios from "axios";
import {Link} from "@inertiajs/vue3";

export default {
	components: {
		Link
	},
    props: {
        username: {
			type: Array,
			required: true
		}
    },
	data() {
		return {
			logs: [],
			observer: null,
		}
	},
	mounted() {
		this.getLogs();

		this.observer = new IntersectionObserver(this.handleIntersection, {
			root: null,
			rootMargin: '0px',
			threshold: 1
		});

		const loadingDiv = this.$refs.loadingDiv;

		if (loadingDiv) {
			this.observer.observe(loadingDiv);
		}
	},
	methods: {
		getLogs(url) {
			const apiUrl = url ? url : '/logs/access';

			axios.get(apiUrl)
				.then(response => {
					if (this.logs.length > 0) {
						this.logs.push(...response.data.data);
					} else {
						this.logs = response.data.data;
					}

					this.nextPage = response.data.next_page_url;
				})
				.catch(error => {
					console.log(error);
				});
		},
		async handleIntersection(entries, observer) {
			if (entries[0].isIntersecting) {
				if (this.nextPage) {
					this.getLogs(this.nextPage);
				}
			}
		},
	}
}
</script>

<style scoped>

</style>
