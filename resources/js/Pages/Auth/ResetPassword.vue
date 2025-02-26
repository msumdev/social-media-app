<template>
    <DarkModeHeader />

    <div class="default-area">
        <div class="default-wrap">
            <FormTop title="Account Recovery" description="we’ll email you a link so you can reset your password." />

            <form class="default-form login bg-white" @submit.prevent="resetPassword">
                <div class="custom-form-group password-from-group input-active pb_20">
                    <label for="password" class="text-blue text-sm pb-3">Password</label>
                    <div class="position-relative">
                        <input id="password" class="input-grey pt_12 pb_12 pl_15 pe-5 round12 grey-background w-100" placeholder="Enter your password" v-model="resetPasswordForm.password" :type="showPassword ? 'text' : 'password'">
                        <button type="button" class="password-eye position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); width: 30px; height: 30px; background: transparent; border: none;" @click="toggleShowPassword">
                            <img src="/images/eye.svg" class="eye" alt="eye" style="width: 20px; height: 20px;">
                        </button>
                    </div>
                </div>

                <div v-if="$page.props.errors.password" class="alert alert-danger" role="alert">
                    {{ $page.props.errors.password }}
                </div>

                <div class="custom-form-group password-from-group input-active pb_20">
                    <label for="confirm-password" class="text-blue text-sm pb-3">Confirm Password</label>
                    <div class="position-relative">
                        <input id="confirm-password" class="input-grey pt_12 pb_12 pl_15 pe-5 round12 grey-background w-100" placeholder="Confirm your password" v-model="resetPasswordForm.confirm_password" :type="showConfirmPassword ? 'text' : 'password'">
                        <button type="button" class="password-eye position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); width: 30px; height: 30px; background: transparent; border: none;" @click="toggleShowConfirmPassword">
                            <img src="/images/eye.svg" class="eye" alt="eye" style="width: 20px; height: 20px;">
                        </button>
                    </div>
                </div>

                <div v-if="$page.props.errors.confirm_password" class="alert alert-danger" role="alert">
                    {{ $page.props.errors.confirm_password }}
                </div>

                <div class="custom-form-group password-from-group input-active mb-5">
                    <div class="password_strength_check mt-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="display-flex-gap d-flex">
                                <span class="strength-step strength-step1" :class="{ active: validatedRuleCount >= 1 }"></span>
                                <span class="strength-step strength-step2" :class="{ active: validatedRuleCount >= 2 }"></span>
                                <span class="strength-step strength-step3" :class="{ active: validatedRuleCount >= 3 }"></span>
                                <span class="strength-step strength-step4" :class="{ active: validatedRuleCount >= 4 }"></span>
                                <span class="strength-step strength-step5" :class="{ active: validatedRuleCount >= 5 }"></span>
                                <span class="strength-step strength-step5" :class="{ active: validatedRuleCount >= 6 }"></span>
                            </div>
                        </div>
                        <div id="password-rules-section" v-if="resetPasswordForm.password.length > 0 && !checkPasswordIsValidated">
                            <p class="text-sm mb-2 fw-bold">Password Requirements:</p>
                            <ul class="text-sm pl-3 mb-0 list-unstyled">
                                <li class="mb-1" v-if="resetPasswordForm.password.length < 8">
                                    Must be <span class="text-primary fw-bold">at least 8 characters long</span>
                                </li>
                                <li class="mb-1" v-if="!/[A-Z]/.test(resetPasswordForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one uppercase letter</span>
                                </li>
                                <li class="mb-1" v-if="!/[a-z]/.test(resetPasswordForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one lowercase letter</span>
                                </li>
                                <li class="mb-1" v-if="!/[0-9]/.test(resetPasswordForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one number</span>
                                </li>
                                <li class="mb-1" v-if="!/[^a-zA-Z0-9]/.test(resetPasswordForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one special character</span>
                                </li>
                                <li class="mb-1" v-if="resetPasswordForm.password !== resetPasswordForm.confirm_password">
                                    Must <span class="text-primary fw-bold">match the confirmed password</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg blue-button w-100" :disabled="!checkPasswordIsValidated">Reset</button>
            </form>

            <p class="text-center text-sm f_500 mt_32 text-form-bottom">
                <span class="text-dark-grey">Already have an account? </span>
                <Link href="/login" class="site-icon text-blue">Log in</Link>
            </p>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import DarkModeHeader from '@/Shared/Auth/DarkModeHeader.vue';
import FormTop from "@/Shared/Auth/FormTop.vue";

export default {
    components: {
        Link,
        DarkModeHeader,
        FormTop
    },
    data() {
        return {
            confirmRules: false,
            showPassword: false,
            showConfirmPassword: false,
            resetPasswordForm: this.$inertia.form({
                password: 'Password1234!',
                confirm_password: 'Password1234!',
            })
        }
    },
    computed: {
        checkPasswordIsValidated() {
            return this.resetPasswordForm.password.length >= 8 &&
                /[A-Z]/.test(this.resetPasswordForm.password) &&
                /[a-z]/.test(this.resetPasswordForm.password) &&
                /[0-9]/.test(this.resetPasswordForm.password) &&
                /[^a-zA-Z0-9]/.test(this.resetPasswordForm.password) &&
                this.resetPasswordForm.password === this.resetPasswordForm.confirm_password;
        },
        validatedRuleCount() {
            const password = this.resetPasswordForm.password;
            const confirmPassword = this.resetPasswordForm.confirm_password;

            let count = 0;

            // Check each rule
            if (password.length >= 8) count++;
            if (/[^a-zA-Z0-9]/.test(password)) count++;
            if (/[0-9]/.test(password)) count++;
            if (/[a-z]/.test(password)) count++;
            if (/[A-Z]/.test(password)) count++;
            if (password && password === confirmPassword) count++; // Ensure password is non-empty

            return count;
        }
    },
    methods: {
        toggleShowPassword() {
            this.showPassword = !this.showPassword;
        },
        toggleShowConfirmPassword() {
            this.showConfirmPassword = !this.showConfirmPassword;
        },
        resetPassword() {
            this.resetPasswordForm.post('/forgotten-password/' + this.$page.props.token);
        }
    }
};
</script>
