<template>
    <DarkModeHeader />

    <div class="default-area">
        <div class="default-wrap">
            <FormTop title="Privacy Policy" description="Please read our privacy policy to understand how we collect, use, and protect your data." />

            <div class="default-form bg-white p-4 round12">
                <h3 class="text-blue text_lg pb-3">Information Collection</h3>
                <ul class="text-sm pl-3 mb-4 list-unstyled">
                    <li class="mb-1">We collect personal information that you voluntarily provide to us when registering on the site.</li>
                    <li class="mb-1">Data collected includes your name, email address, and any other details you choose to share.</li>
                    <li class="mb-1">We also gather information automatically through your use of the site, such as IP address, browser type, and pages visited.</li>
                </ul>

                <h3 class="text-blue text_lg pb-3">Use of Information</h3>
                <ul class="text-sm pl-3 mb-4 list-unstyled">
                    <li class="mb-1">Your information is used to provide, operate, and improve our services.</li>
                    <li class="mb-1">We may use your email address to send you important notifications and updates about our services.</li>
                    <li class="mb-1">Your data may be used for research and analysis to enhance the user experience.</li>
                </ul>

                <h3 class="text-blue text_lg pb-3">Information Sharing</h3>
                <ul class="text-sm pl-3 mb-4 list-unstyled">
                    <li class="mb-1">We do not share your personal information with third parties without your consent, except as required by law.</li>
                    <li class="mb-1">We may share your data with service providers who assist us in operating our site, under strict confidentiality agreements.</li>
                    <li class="mb-1">Aggregated and anonymized data may be shared for research or marketing purposes.</li>
                </ul>

                <h3 class="text-blue text_lg pb-3">Data Security</h3>
                <ul class="text-sm pl-3 mb-4 list-unstyled">
                    <li class="mb-1">We implement security measures to protect your personal information from unauthorized access.</li>
                    <li class="mb-1">Your data is stored securely, and we regularly review our security practices to keep your information safe.</li>
                    <li class="mb-1">Despite our efforts, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</li>
                </ul>

                <h3 class="text-blue text_lg pb-3">Your Rights</h3>
                <ul class="text-sm pl-3 mb-0 list-unstyled">
                    <li class="mb-1">You have the right to access, correct, or delete your personal information stored on our site.</li>
                    <li class="mb-1">You may also request to limit the processing of your data or opt-out of marketing communications.</li>
                    <li class="mb-1">To exercise your rights, please contact our support team for assistance.</li>
                </ul>
            </div>
            <p class="text-center text-sm f_500 mt_32 text-form-bottom">
                <span class="text-dark-grey">Thank you for trusting us with your information. Back to registration? <Link href="/registration" class="site-icon text-blue">Registration</Link></span>
            </p>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3"
import DarkModeHeader from '@/Shared/Auth/DarkModeHeader.vue';
import FormTop from "@/Shared/Auth/FormTop.vue";

export default {
    components: {
        Link,
        DarkModeHeader,
        FormTop
    }
};
</script>
