<template>
    <div :class="['section-wrapper']" :style="'background-color: ' + user.profile.user_info_background_colour + '!important'">
        <!-- profile-top -->
		<div class="row mb-4 mt-2 align-items-center">
			<!-- Profile Picture -->
			<div class="col-12 col-sm-4 col-md-2 text-center">
				<div class="profile-top">
					<a href="#" class="profile-img profile-img_100 position-relative me-3 mb-3 mb-md-0 d-inline-block">
						<img :src="profile.profile_picture" alt="admin_img" class="rounded-circle img-fluid">
						<div class="active_dot active position-absolute bottom-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle"></div>
					</a>
				</div>
			</div>
			<!-- Name -->
			<div class="col-6 col-md-2 text-center">
				<h4 class="title16 text-blue" :style="'color: ' + user.profile.user_info_text_colour + '!important'">{{ profile.first_name }}</h4>
				<p class="text-sm text-dark-grey">Name</p>
			</div>
			<!-- Username -->
			<div class="col-6 col-md-2 text-center">
				<h4 class="title16 text-blue" :style="'color: ' + user.profile.user_info_text_colour + '!important'">@{{ profile.username }}</h4>
				<p class="text-sm text-dark-grey">Username</p>
			</div>
			<!-- Age -->
			<div class="col-6 col-md-2 text-center">
				<h4 class="title16 text-blue" :style="'color: ' + user.profile.user_info_text_colour + '!important'">{{ profile.age }}</h4>
				<p class="text-sm text-dark-grey">Age</p>
			</div>
			<!-- Gender -->
			<div class="col-6 col-md-2 text-center">
				<h4 class="title16 text-blue" :style="'color: ' + user.profile.user_info_text_colour + '!important'">{{ profile.sex?.name }}</h4>
				<p class="text-sm text-dark-grey">Gender</p>
			</div>
			<!-- Hometown -->
			<div class="col-6 col-md-2 text-center">
				<h4 class="title16 text-blue" :style="'color: ' + user.profile.user_info_text_colour + '!important'">{{ profile.city?.name }}</h4>
				<p class="text-sm text-dark-grey">Hometown</p>
			</div>
		</div>

		<div class="row">
            <div class="col-12">
                <label for="email" class="text-blue text-sm pb-3" :style="'color: ' + user.profile.user_info_text_colour + '!important'">Spoken Languages</label>
                <v-select id="languages" label="name" :options="languages" v-model="profile.languages" v-if="editing" multiple></v-select>

                <div class="select-options d-flex align-items-center gap_8" v-if="!editing">
                    <button type="button" class="language-tag d-flex align-items-center" v-for="language in profile.languages" :key="language.id">
                        <p class="text_xsm">{{ language.name }}</p>
                    </button>
                </div>
            </div>
            <div class="col-12">
                <label for="email" class="text-blue text-sm pb-3" :style="'color: ' + user.profile.user_info_text_colour + '!important'">Interests</label>
                <v-select id="interests" label="name" :options="interests" v-model="profile.interests" v-if="editing" multiple></v-select>

                <div class="select-options d-flex align-items-center gap_8" v-if="!editing">
                    <button type="button" class="language-tag d-flex align-items-center" v-for="interest in profile.interests" :key="interest.id">
                        <p class="text_xsm">{{ interest.name }}</p>
                    </button>
                </div>
            </div>
            <div class="col-12">
                <label for="email" class="text-blue text-sm pb-3" :style="'color: ' + user.profile.user_info_text_colour + '!important'">About me </label>

                <div class="input-group" v-if="editing">
                    <Editor v-model="profile.profile.description" :style="'background-color: ' + user.profile.about_me_background_colour + '!important'" />
                </div>

                <div class="alert m-0" :style="'background-color: ' + user.profile.about_me_background_colour + '!important'" role="alert" v-html="processedContent" v-if="!editing"></div>
            </div>
			<div class="col-12" v-if="editing">
				<div class="row">
					<div class="col-3">
						<div class="mb-3">
							<label for="user-info-background-colour" class="text-blue form-label h5">Background Colour</label>
							<input type="color" id="user-info-background-colour" name="user-info-background-colour" v-model="user.profile.user_info_background_colour" style="display: block; width: 100%;" />
						</div>
					</div>
					<div class="col-3">
						<div class="mb-3">
							<label for="user-info-text-colour" class="text-blue form-label h5">Text Colour</label>
							<input type="color" id="user-info-text-colour" name="user-info-text-colour" v-model="user.profile.user_info_text_colour" style="display: block; width: 100%;" />
						</div>
					</div>
					<div class="col-3">
						<div class="mb-3">
							<label for="about-me-background-colour" class="text-blue form-label h5">About Me Colour</label>
							<input type="color" id="about-me-background-colour" name="about-me-background-colour" v-model="user.profile.about_me_background_colour" style="display: block; width: 100%;" />
						</div>
					</div>
				</div>
			</div>
            <div class="col-12 d-flex justify-content-end">
				<button class="text-decoration-none text-body icon-circle me-3" @click="toggleEditing" v-if="user.id && profile.id && user.id === profile.id">
					<i
						:class="[
							'fs-5',
							editing ? 'bi-x-circle-fill' : 'bi-pencil-fill'
						]"
					></i>
				</button>

				<button type="button" class="btn btn-main btn-lg me-3" @click="resetStyle" v-if="editing">Reset</button>
				<button type="button" class="btn btn-main btn-lg" v-if="editing">Save</button>
            </div>
        </div>
    </div>

	<div class="section-wrapper mt-4">
		<h2 class="title16">Comments</h2>
		<NewProfileComment :user="user" :username="profile.username" @comment-added="getComments" />
		<div class="wall_comments">
			<div class="comment-box">
				<div class="default-post-wrap" v-for="comment in comments.data" v-if="comments">
					<div class="comment-text">
						<div class="default-comment d-flex gap_x_12 border_t1 p_12 round12" :class="{ 'default-comment-post': comment.user.id === user.id }">
							<div href="#" class="profile-img profile-img_48 mr_10">
								<img :src="comment.user.profile_picture" alt="Avatar9">
							</div>
							<div class="default-comment-texts w-100">
								<div class="d-flex align-items-center justify-content-between">
									<div class="d-flex align-items-center">
										<a href="#" class="text_15 f_700 site-icon">{{ comment.user.first_name }}</a>
										<p class="text-dark-grey text_xsm f_400 pl_5"> {{ comment.user.age }} . </p>
									</div>
									<p class="text-dark-grey text_xsm f_500" :title="comment.created_at_title">{{ comment.created_at_display }}</p>
								</div>
								<p class="text_15 f_400 pb-3 pt_8" v-html="comment.content"></p>

								<AudioRecording v-if="comment.assets.length > 0" v-for="asset in comment.assets" :key="asset" :container-classes="'mb-4'" is-embedded="true" :path="asset.url" />

								<div class="d-flex align-items-center gap_x_12 text_xsm">
									<button type="button" class="translation_btn f_600">Translate</button>
									<button type="button" class="comment_delete_btn f_600" v-if="user.id === comment.user.id" @click="deleteComment(comment._id)">Delete</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="default-post-wrap" v-if="comments.total === 0">
					<p class="text-center text-muted">No comments yet.</p>
				</div>
				<nav class="d-flex justify-content-center flex-wrap mt-3" aria-label="Comment Pagination" v-if="comments.total > 0">
					<ul class="pagination pagination-sm">
						<li
							class="page-item"
							:class="{ 'disabled': !link.url, 'active': link.active }"
							v-for="(link, index) in comments.links"
							:key="index">
							<button class="page-link fs-4" :disabled="!link.url" v-html="link.label" @click="getComments(link.url)"></button>
						</li>
					</ul>
				</nav>

			</div>
		</div>
	</div>
</template>

<script>
import {useToast} from "vue-toastification";
import { defineAsyncComponent } from 'vue';
import axios from "axios";
import DOMPurify from "dompurify";
import {Link} from "@inertiajs/vue3";
import AudioRecording from "../../AudioRecording.vue";

export default {
    components: {
		AudioRecording,
		Link,
		NewProfileComment: defineAsyncComponent(() => import("./NewProfileComment.vue")),
		vSelect: defineAsyncComponent(() => import("vue-select")),
        Editor: defineAsyncComponent(() => import("@/Shared/Editor.vue")),
    },
    props: {
        user: {
            type: Object,
            required: true
        },
        profile: {
            type: Object,
            required: true
        }
    },
	data() {
		return {
			toast: null,
			editing: false,
			languages: [],
			interests: [],
			comments: []
		}
	},
	mounted() {
		this.toast = useToast();

		this.getLanguages();
		this.getInterests();
		this.getComments();
	},
    computed: {
        processedContent() {
            if (!this.profile.profile.description) {
                return '';
            }

            this.profile.profile.description = this.profile.profile.description.replace(/<p><\/p>/g, '<p>&nbsp;</p>');

            return this.withReplacements(this.profile.profile.description, this.profile.settings.render_media);
        }
    },
	methods: {
		getLanguages() {
			axios.get("/languages")
				.then(response => {
					this.languages = response.data;
				})
				.catch(error => {
					this.toast.error("Failed to fetch languages");
				});
		},
		getInterests() {
			axios.get("/interests")
				.then(response => {
					this.interests = response.data;
				})
				.catch(error => {
					this.toast.error("Failed to fetch languages");
				});
		},
        withReplacements(content, render_media = false) {
            content = DOMPurify.sanitize(content);

            if (render_media) {
                const youtubeRegex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})([^\s]*)?/g;
                const matches = [...content.matchAll(youtubeRegex)];

                for (const match of matches) {
                    const videoId = match[1];

                    let url = match[0];

                    url = url.replace(/&amp;/g, '&').replace(/<\/?[^>]+(>|$)/g, "");

                    const urlParams = new URLSearchParams(url.split('?')[1]);
                    const timestamp = urlParams.get('t') ? parseInt(urlParams.get('t')) : null;
                    const embedUrl = `https://www.youtube.com/embed/${videoId}?start=${timestamp}`;
                    const iframeElement = `<div class="ratio ratio-16x9 mt-4"><iframe class="embed-responsive-item" src="${embedUrl}" frameborder="0" allowfullscreen></iframe></div>`;

                    content = content.replace(match[0], iframeElement);
                }
            }

            return content;
        },
        toggleEditing() {
            this.editing = !this.editing;
        },
		resetStyle() {
			this.user.profile.user_info_background_colour = '#ffffff';
			this.user.profile.user_info_text_colour = '#4182eb';
			this.user.profile.about_me_background_colour = '#F8F9FA';
		},
		onEditorInput(output) {
			this.addPostForm.content = output.content;
			this.addPostForm.hashtags = output.hashtags;
			this.addPostForm.mentions = output.mentions;
		},
		getComments(nextLink = null) {
			let url = null;

			if (nextLink) {
				url = nextLink;
			} else {
				url = `/u/${this.profile.username}/comments`;
			}

			axios.get(url)
				.then(response => {
					this.comments = response.data;
				})
				.catch(error => {
					this.toast.error("Failed to fetch comments");
				});
		},
		deleteComment(id) {
			axios.delete(`/u/${this.profile.username}/comments/${id}`)
				.then(response => {
					this.toast.success(response.data.message);

					this.getComments();
				})
				.catch(error => {
					this.toast.error(error.response.data.message);
				});
		}
	}
}
</script>

<style scoped>
.icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px; /* Adjust size as needed */
    height: 40px; /* Adjust size as needed */
    border-radius: 50%; /* Makes it a circle */
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.icon-circle:hover {
    background-color: rgba(0, 0, 0, 0.1); /* Add a light background on hover */
    transform: scale(1.1); /* Optional: slightly enlarge the circle */
}

.night-mode >>> {
    --vs-colors--lightest: rgba(255, 255, 255, 0.2); /* Subtle light effect for borders */
    --vs-colors--light: rgba(255, 255, 255, 0.5); /* Moderate light for borders or text */
    --vs-colors--dark: #e5e7eb; /* Soft white for text */
    --vs-colors--darkest: rgba(0, 0, 0, 0.6); /* Shadow for dropdowns */

    /* Search Input */
    --vs-search-input-color: #ffffff; /* White text for input */
    --vs-search-input-bg: #1f2937; /* Dark gray background */
    --vs-search-input-placeholder-color: rgba(255, 255, 255, 0.6); /* Lighter placeholder for contrast */

    /* Font */
    --vs-font-size: 1rem;
    --vs-line-height: 1.4;

    /* Disabled State */
    --vs-state-disabled-bg: #374151; /* Subtle gray for disabled background */
    --vs-state-disabled-color: var(--vs-colors--light); /* Muted text for disabled state */
    --vs-state-disabled-controls-color: var(--vs-colors--light); /* Matching muted controls */
    --vs-state-disabled-cursor: not-allowed;

    /* Borders */
    --vs-border-color: var(--vs-colors--lightest); /* Light borders for components */
    --vs-border-width: 1px;
    --vs-border-style: solid;
    --vs-border-radius: 4px;

    /* Actions: house the component controls */
    --vs-actions-padding: 4px 6px 0 3px;

    /* Component Controls: Clear, Open Indicator */
    --vs-controls-color: rgba(255, 255, 255, 0.7); /* Lighter controls for visibility */
    --vs-controls-size: 1;
    --vs-controls--deselect-text-shadow: 0 1px 0 #000; /* Subtle shadow for contrast */

    /* Selected */
    --vs-selected-bg: #374151; /* Medium gray for selected background */
    --vs-selected-color: #e5e7eb; /* Light text for selected items */
    --vs-selected-border-color: var(--vs-border-color); /* Matching border with components */
    --vs-selected-border-style: var(--vs-border-style);
    --vs-selected-border-width: var(--vs-border-width);

    /* Dropdown */
    --vs-dropdown-bg: #1f2937; /* Dark background for dropdown */
    --vs-dropdown-color: #ffffff; /* White text for dropdown items */
    --vs-dropdown-z-index: 1000;
    --vs-dropdown-min-width: 160px;
    --vs-dropdown-max-height: 350px;
    --vs-dropdown-box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.7); /* Subtle shadow for depth */

    /* Options */
    --vs-dropdown-option-bg: #2d3748; /* Dark gray for options */
    --vs-dropdown-option-color: var(--vs-dropdown-color); /* Matching text with dropdown */
    --vs-dropdown-option-padding: 6px 20px; /* Comfortable padding for options */

    /* Active State */
    --vs-dropdown-option--active-bg: #3b82f6; /* Vibrant blue for active option */
    --vs-dropdown-option--active-color: #ffffff; /* White text for contrast */

    /* Deselect State */
    --vs-dropdown-option--deselect-bg: #ef4444; /* Bright red for danger state */
    --vs-dropdown-option--deselect-color: #ffffff; /* White text for contrast */

    /* Transitions */
    --vs-transition-timing-function: cubic-bezier(0.4, 0.0, 0.2, 1); /* Smooth standard easing */
    --vs-transition-duration: 150ms;
}
</style>
