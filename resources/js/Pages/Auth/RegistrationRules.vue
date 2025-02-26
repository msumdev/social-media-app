<template>
    <DarkModeHeader />

    <div class="default-area">
        <div class="default-wrap">
            <FormTop title="Site Rules" description="Please review the following rules to ensure a safe and enjoyable experience on our site." />

            <div class="default-form bg-white p-4 round12">
                <h3 class="text-blue text_lg pb-3">General Conduct</h3>
                <ul class="text-sm pl-3 mb-4 list-unstyled">
                    <li class="mb-1">Be respectful to other members.</li>
                    <li class="mb-1">No hate speech, harassment, or discrimination.</li>
                    <li class="mb-1">Do not share personal information without consent.</li>
                </ul>

                <h3 class="text-blue text_lg pb-3">Content Guidelines</h3>
                <ul class="text-sm pl-3 mb-4 list-unstyled">
                    <li class="mb-1">No posting of illegal content.</li>
                    <li class="mb-1">Ensure your content is appropriate for all audiences.</li>
                    <li class="mb-1">Do not plagiarize or steal content from others.</li>
                </ul>

                <h3 class="text-blue text_lg pb-3">Account Security</h3>
                <ul class="text-sm pl-3 mb-4 list-unstyled">
                    <li class="mb-1">Use a strong and unique password for your account.</li>
                    <li class="mb-1">Enable two-factor authentication for added security.</li>
                    <li class="mb-1">Do not share your account credentials with others.</li>
                </ul>

                <h3 class="text-blue text_lg pb-3">Reporting Issues</h3>
                <ul class="text-sm pl-3 mb-0 list-unstyled">
                    <li class="mb-1">Report any violations of these rules to our support team.</li>
                    <li class="mb-1">Use the report feature to notify us of inappropriate content.</li>
                    <li class="mb-1">Contact support if you experience any issues or need assistance.</li>
                </ul>
            </div>
            <p class="text-center text-sm f_500 mt_32 text-form-bottom">
                <span class="text-dark-grey">Thank you for helping us maintain a positive and safe community. Back to registration? <Link href="/registration" class="site-icon text-blue">Registration</Link></span>
            </p>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import DarkModeHeader from '@/Shared/Auth/DarkModeHeader.vue';
import FormTop from "@/Shared/Auth/FormTop.vue";

export default {
    components: {
        Link,
        DarkModeHeader,
        FormTop,
    }
};
</script>
