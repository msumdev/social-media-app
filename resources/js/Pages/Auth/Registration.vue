<template>
    <DarkModeHeader />

    <div class="default-area">
        <div class="default-wrap">
            <FormTop title="Create an account" description="Join us and start your journey! Please fill in your details to create an account." />

            <form class="default-form login bg-white" @submit.prevent="register">
                <div class="custom-form-group mb_20">
                    <label for="username" class="text-blue text-sm pb-3">Username</label>
                    <input id="username" type="text" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100" placeholder="Enter your username" autocomplete="username" v-model="registrationForm.username">
                </div>

                <div v-if="$page.props.errors.username" class="alert alert-danger" role="alert">
                    {{ $page.props.errors.username }}
                </div>

                <div class="custom-form-group mb_20">
                    <label for="email" class="text-blue text-sm pb-3">Email</label>
                    <input id="email" type="text" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100" placeholder="Enter your email address" autocomplete="email" v-model="registrationForm.email">
                </div>

                <div v-if="$page.props.errors.email" class="alert alert-danger" role="alert">
                    {{ $page.props.errors.email }}
                </div>

                <div class="custom-form-group mb_20">
                    <label for="date_of_birth" class="text-blue text-sm pb-3">Date of Birth</label>
                    <Datepicker id="date_of_birth" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100" placeholder="Select your date of birth"  v-model="registrationForm.date_of_birth" starting-view="year"></Datepicker>
                </div>

                <div v-if="$page.props.errors.date_of_birth" class="alert alert-danger" role="alert">
                    {{ $page.props.errors.date_of_birth }}
                </div>

                <div class="row">
                    <div class="col-md-6 mb_20">
                        <div class="custom-form-group">
                            <label for="first_name" class="text-blue text-sm pb-3">First Name</label>
                            <input id="first_name" type="text" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100" placeholder="Enter your first name" autocomplete="first_name" v-model="registrationForm.first_name">
                        </div>
                    </div>
                    <div class="col-md-6 mb_20">
                        <div class="custom-form-group">
                            <label for="last_name" class="text-blue text-sm pb-3">Last Name</label>
                            <input id="last_name" type="text" class="input-grey pt_12 pb_12 pl_15 pr_15 round12 grey-background w-100" placeholder="Enter your last name" v-model="registrationForm.last_name">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div v-if="$page.props.errors.first_name" class="alert alert-danger" role="alert">
                            {{ $page.props.errors.first_name }}
                        </div>

                        <div v-if="$page.props.errors.last_name" class="alert alert-danger" role="alert">
                            {{ $page.props.errors.last_name }}
                        </div>
                    </div>
                </div>

                <div class="custom-form-group mb_20">
                    <label for="country" class="text-blue text-sm pb-3">Country</label>
                    <v-select id="country" label="name" :options="countries" v-model="registrationForm.country" @option:selected="selectCountry"></v-select>
                </div>

                <div v-if="$page.props.errors.country" class="alert alert-danger" role="alert">
                    The country field is required
                </div>

                <div class="custom-form-group mb_20" v-if="registrationForm.country && cities.length > 0">
                    <label for="city" class="text-blue text-sm pb-3">City</label>
                    <v-select id="city" label="name" :options="cities" v-model="registrationForm.city"></v-select>
                </div>

                <div class="custom-form-group mb_20">
                    <label for="gender" class="text-blue text-sm pb-3">Gender</label>
                    <v-select id="gender" label="name" :options="genders" v-model="registrationForm.gender"></v-select>
                </div>

                <div v-if="$page.props.errors.gender" class="alert alert-danger" role="alert">
                    The gender field is required
                </div>

                <div class="custom-form-group mb_20">
                    <label for="sex" class="text-blue text-sm pb-3">Sex</label>
                    <v-select id="sex" label="name" :options="sexes" v-model="registrationForm.sex"></v-select>
                </div>

                <div v-if="$page.props.errors.sex" class="alert alert-danger" role="alert">
                    The sex field is required
                </div>

                <div class="custom-form-group password-from-group input-active pb_20">
                    <label for="password" class="text-blue text-sm pb-3">Password</label>
                    <div class="position-relative">
                        <input id="password" class="input-grey pt_12 pb_12 pl_15 pe-5 round12 grey-background w-100" placeholder="Enter your password" v-model="registrationForm.password" :type="showPassword ? 'text' : 'password'">
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
                        <input id="confirm-password" class="input-grey pt_12 pb_12 pl_15 pe-5 round12 grey-background w-100" placeholder="Confirm your password" v-model="registrationForm.confirm_password" :type="showConfirmPassword ? 'text' : 'password'">
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
                        <div id="password-rules-section" v-if="registrationForm.password.length > 0 && !checkPasswordIsValidated">
                            <p class="text-sm mb-2 fw-bold">Password Requirements:</p>
                            <ul class="text-sm pl-3 mb-0 list-unstyled">
                                <li class="mb-1" v-if="registrationForm.password.length < 8">
                                    Must be <span class="text-primary fw-bold">at least 8 characters long</span>
                                </li>
                                <li class="mb-1" v-if="!/[A-Z]/.test(registrationForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one uppercase letter</span>
                                </li>
                                <li class="mb-1" v-if="!/[a-z]/.test(registrationForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one lowercase letter</span>
                                </li>
                                <li class="mb-1" v-if="!/[0-9]/.test(registrationForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one number</span>
                                </li>
                                <li class="mb-1" v-if="!/[^a-zA-Z0-9]/.test(registrationForm.password)">
                                    Must contain <span class="text-primary fw-bold">at least one special character</span>
                                </li>
                                <li class="mb-1" v-if="registrationForm.password !== registrationForm.confirm_password">
                                    Must <span class="text-primary fw-bold">match the confirmed password</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="blue-button w-100">Register</button>

                <p class="text-center text-sm f_500 mt_12 text-form-bottom">
                    <span class="text-dark-grey">By signing up, you agree to our <Link href="/registration/rules" class="site-icon text-blue">Terms of Use</Link> and <br>
                        <Link href="/registration/privacy-policy" class="site-icon text-blue">Privacy Policy</Link>
                    </span>
                </p>
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
import axios from "axios"
import vSelect from 'vue-select';
import DarkModeHeader from "@/Shared/Auth/DarkModeHeader.vue";
import FormTop from "@/Shared/Auth/FormTop.vue";

export default {
    components: {
        DarkModeHeader,
        FormTop,
        Link,
        Datepicker,
        vSelect
    },
    data() {
        return {
            confirmRules: false,
            showPassword: false,
            showConfirmPassword: false,
            registrationForm: this.$inertia.form({
                username: 'username',
                email: 'test@test.com',
                date_of_birth: new Date(new Date().setFullYear(new Date().getFullYear() - 16)),
                first_name: 'Test',
                last_name: 'Test',
                password: 'Password1234!',
                confirm_password: 'Password1234!',
                country: {
                    id: 1,
                    name: 'Afghanistan',
                    code: 'AF'
                },
                city: {},
                gender: {
                    id: 1,
                    name: "Male"
                },
                sex: {
                    id: 1,
                    name: "Male"
                }
            }),
            countries: [],
            cities: [],
            genders: [],
            sexes: []
        }
    },
    mounted() {
        this.getCountries();
        this.getGenders();
        this.getSexes();
    },
    computed: {
        checkPasswordIsValidated() {
            return this.registrationForm.password.length >= 8 &&
                /[A-Z]/.test(this.registrationForm.password) &&
                /[a-z]/.test(this.registrationForm.password) &&
                /[0-9]/.test(this.registrationForm.password) &&
                /[^a-zA-Z0-9]/.test(this.registrationForm.password) &&
                this.registrationForm.password === this.registrationForm.confirm_password;
        },
        validatedRuleCount() {
            const password = this.registrationForm.password;
            const confirmPassword = this.registrationForm.confirm_password;

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
    watch: {
        'registrationForm.country'(newVal, oldVal) {
            if (!newVal) {
                this.resetLocation();
            }
        },
    },
    methods: {
        toggleRulesConfirmation() {
            this.confirmRules = !this.confirmRules;
        },
        toggleShowPassword() {
            this.showPassword = !this.showPassword;
        },
        toggleShowConfirmPassword() {
            this.showConfirmPassword = !this.showConfirmPassword;
        },
        register() {
            this.registrationForm
                .transform((data) => ({
                    ...data,
                    country: (data.country) ? data.country.id : null,
                    city: (data.city) ? data.city.id : null,
                    gender: (data.gender) ? data.gender.id : null,
                    sex: (data.sex) ? data.sex.id : null,
                    date_of_birth: data.date_of_birth ? this.extractDate(data.date_of_birth) : null
                }))
                .post('/registration');
        },
        async getCountries() {
            await axios.get('/api/countries')
                .then(response => {
                    this.countries = response.data.countries;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        async selectCountry(country) {
            await axios.get(`/api/country/${country.id}`)
                .then(response => {
                    this.cities = response.data.cities;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        async getGenders() {
            await axios.get(`/api/genders`)
                .then(response => {
                    this.genders = response.data.genders;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        async getSexes(country) {
            await axios.get(`/api/sexes`)
                .then(response => {
                    this.sexes = response.data.sexes;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        resetLocation() {
            this.registrationForm.city = null;
            this.cities = [];
        },
        extractDate(date) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
        }
    }
};
</script>
