<template>
    <!-- Modal -->
    <div class="modal fade" :id="modalId" tabindex="-1" :aria-labelledby="modalId + '-label'">
        <div class="modal-dialog modal-dialog-centered rounded-0">
            <div class="modal-content ">
                <div class="modal-header bg_blue">
                    <p class="text-white fs-4">Report Post</p>
                    <button type="button" class="btn-close btn-close-white fs-6" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label fs-5">
                            <strong>Reason for reporting this post?</strong>
                            <i
                                class="bi bi-info-circle ms-2"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                title="Select a reason that best describes your concern."
                            ></i>
                        </label>
                        <div class="row">
                            <div class="col-12">
                                <button
                                    type="button"
                                    class="btn mt-2 me-2 mb-2"
                                    v-for="(reason, index) in reasons"
                                    :class="report.reasons.includes(index) ? 'btn-primary' : 'btn-light-grey'"
                                    @click="addReason(index)"
                                >{{ reason.name }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label fs-5">
                            <strong>Can you provide more information?</strong>
                            <i
                                class="bi bi-info-circle ms-2"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip"
                                title="Provide more information about why you are reporting this post for example if you are reporting for harassment, provide the specific comments or messages that are harassing."
                            ></i>
                        </label>
                        <div class="row">
                            <div class="col-12">
                                <textarea
                                    class="form-control"
                                    id="description"
                                    v-model="report.description"
                                    rows="5"
                                    placeholder="Provide more information about why you are reporting this post"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-main" @click="submit">Submit Report</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {useToast} from "vue-toastification";
import vSelect from "vue-select";
import { v4 as uuidv4 } from 'uuid';

export default {
    components: {
        vSelect
	},
    props: {
        postId: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            modalId: uuidv4(),
            reason: '',
            reasons: [],
            toast: null,
            report: {
                reasons: [],
                description: ''
            }
        }
    },
    mounted() {
        this.toast = useToast();

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    },
    methods: {
        toggle() {
            if (this.reasons.length === 0) {
                this.getReasons();
            }

            this.report.reasons = [];
            this.report.description = '';

            const modal = bootstrap.Modal.getOrCreateInstance('#' + this.modalId);
            modal.show();
        },
        getReasons() {
            axios.get('/report-reasons').then(response => {
                this.reasons = response.data;
            }).catch(error => {
                this.toast.error('Failed to get report reasons');
            });
        },
        addReason(index) {
            if (!this.report.reasons.includes(index)) {
                this.report.reasons.push(index);
            } else {
                this.report.reasons = this.report.reasons.filter(reason => reason !== index);
            }
        },
        submit() {
            axios.post(`/post/${this.postId}/report`, this.report).then(response => {
                this.toast.success(response.data.message);
            }).catch(error => {
                this.toast.error(error.response.data.message);
            });
        },
    }
}
</script>
