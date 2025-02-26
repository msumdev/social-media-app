<template>
    <DarkModeHeader />

    <div class="default-area">
        <div class="default-wrap">
            <FormTop title="Account Recovery" description="we’ll email you a link so you can reset your password." />

            <form class="default-form login bg-white" @submit.prevent="resetPassword">
                <Alerts />

                <div class="custom-form-group mb_20">
                    <label for="email" class="text-blue text-sm pb-3">Email</label>
                    <input type="text" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100" placeholder="Enter your email" v-model="forgottenPasswordForm.email">
                </div>
                <div class="custom-form-group mb_20">
                    <label for="date_of_birth" class="text-blue text-sm pb-3">Date of Birth</label>
                    <Datepicker id="date_of_birth" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100" placeholder="Select your date of birth"  v-model="forgottenPasswordForm.date_of_birth" starting-view="year"></Datepicker>
                </div>

                <button type="submit" class="blue-button w-100">Reset Password</button>
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
import Datepicker from 'vue3-datepicker'
import DarkModeHeader from "../../Shared/Auth/DarkModeHeader.vue";
import Alerts from "../../Shared/Auth/Alerts.vue";
import FormTop from "../../Shared/Auth/FormTop.vue";

export default {
    components: {
        DarkModeHeader,
        Alerts,
        FormTop,
        Link,
        Datepicker
    },
    data() {
        return {
            keepMeLoggedIn: false,
            forgottenPasswordForm: this.$inertia.form({
                email: 'test@test.com',
                date_of_birth: null,
            })
        }
    },
    methods: {
        resetPassword() {
            this.forgottenPasswordForm
                .transform((data) => {
                    return {
                        ...data,
                        date_of_birth: data.date_of_birth ? this.extractDate(data.date_of_birth) : null
                    }
                })
                .post('/forgotten-password');
        },
        extractDate(date) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        }
    }
};
</script>
