<template>
    <portal to="header">
        <h1 class="text-4xl font-bold text-center text-blue-500 mb-4 mt-8">
            Registration
        </h1>
        <h2 class="text-xl text-center text-gray-800 mb-8 dark:text-gray-400">
            Welcome! Fill in the form below to join the community!
        </h2>
    </portal>

    <form @submit.prevent="submit()">
        <div class="mb-5">
            <label for="username" class="block mb-2 text-md text-blue-600 dark:text-white">Username</label>
            <input type="text" id="username" class="primary-input" placeholder="Enter your username" v-model="form.username" />
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.username">{{ form.errors.username }}</div>
        </div>
        <div class="mb-5">
            <label for="email" class="block mb-2 text-md text-blue-600 dark:text-white">Email</label>
            <input type="text" id="email" class="primary-input" placeholder="Enter your email address" v-model="form.email" />
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.email">{{ form.errors.email }}</div>
        </div>
        <div class="mb-5">
            <label for="date-of-birth" class="block mb-2 text-md text-blue-600 dark:text-white">Date of Birth</label>
            <DatePicker id="date-of-birth" v-model="form.date_of_birth"></DatePicker>
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.date_of_birth">{{ form.errors.date_of_birth }}</div>
        </div>
        <div class="mb-5 grid grid-cols-2 gap-4">
            <div class="col-span-2 md:col-span-1">
                <label for="first-name" class="block mb-2 text-md text-blue-600 dark:text-white">First Name</label>
                <input type="text" id="first-name" class="primary-input" placeholder="Enter your first name" v-model="form.first_name" />
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.first_name">{{ form.errors.first_name }}</div>
            </div>
            <div class="col-span-2 md:col-span-1">
                <label for="last-name" class="block mb-2 text-md text-blue-600 dark:text-white">Last Name</label>
                <input type="text" id="last-name" class="primary-input" placeholder="Enter your last name" v-model="form.last_name" />
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.last_name">{{ form.errors.last_name }}</div>
            </div>
        </div>
        <div class="mb-5 grid grid-cols-2 gap-4">
            <div class="col-span-2 md:col-span-1">
                <label for="sex" class="block mb-2 text-md text-blue-600 dark:text-white">Sex</label>
                <Select id="sex" :options="sexes" v-model="form.sex_id" :append-to-body="true"></Select>
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.sex_id">{{ form.errors.sex_id }}</div>
            </div>
            <div class="col-span-2 md:col-span-1">
                <label for="gender" class="block mb-2 text-md text-blue-600 dark:text-white">Gender</label>
                <Select
                    id="gender"
                    :options="genders"
                    v-model="form.gender_id"
                ></Select>
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.gender_id">{{ form.errors.gender_id }}</div>
            </div>
        </div>
        <div class="mb-5 grid grid-cols-2 gap-4">
            <div :class="cities.length > 0 ? 'col-span-2 md:col-span-1' : 'col-span-2'">
                <label for="country" class="block mb-2 text-md text-blue-600 dark:text-white">Country</label>
                <Select
                    id="country"
                    :options="countries"
                    @changed="countrySelected"
                    @option-deselected="countryDeselected"
                    v-model="form.country_id"
                ></Select>
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.country_id">{{ form.errors.country_id }}</div>
            </div>
            <div class="col-span-2 md:col-span-1" v-if="cities.length > 0">
                <label for="city" class="block mb-2 text-md text-blue-600 dark:text-white">Cities</label>
                <Select id="city" :options="cities" v-model="form.city_id"></Select>
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.city_id">{{ form.errors.city_id }}</div>
            </div>
        </div>
        <div class="mb-5">
            <label for="sexuality" class="block mb-2 text-md text-blue-600 dark:text-white">Sexuality</label>
            <Select id="sexuality" :options="sexualities" v-model="form.sexuality_id"></Select>
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.sexuality_id">{{ form.errors.sexuality_id }}</div>
        </div>
        <div class="mb-5">
            <label for="interests" class="block mb-2 text-md text-blue-600 dark:text-white">Interests</label>
            <Select
                id="interests"
                :is-multi="true"
                :options="interests"
                v-model="form.interests"
                @option-deselected="onInterestRemoved"
            ></Select>
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.interests">{{ form.errors.interests }}</div>
        </div>
        <div class="mb-5 grid grid-cols-2 gap-4">
            <div class="col-span-2 md:col-span-1">
                <label for="password" class="block mb-2 text-md text-blue-600 dark:text-white">Password</label>
                <input type="password" id="password" class="primary-input" placeholder="Enter your password" v-model="form.password" />
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.password">{{ form.errors.password }}</div>
            </div>
            <div class="col-span-2 md:col-span-1">
                <label for="confirmPassword" class="block mb-2 text-md text-blue-600 dark:text-white">Confirm Password</label>
                <input type="password" id="confirmPassword" class="primary-input" placeholder="Confirm your password" v-model="form.confirm_password" />
                <div class="text-red-600 text-sm mt-2" v-if="form.errors.confirm_password">{{ form.errors.confirm_password }}</div>
            </div>
        </div>
        <button type="submit" class="primary-button w-full">
            Register
        </button>
    </form>

    <portal to="footer">
        <div class="mb-8">
            Already have an account?
            <Link href="/login" class="default-link">Login</Link>
        </div>
    </portal>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3'
import axios from "axios";
import AuthNavigationBar from '@/Components/Navigation/AuthNavigationBar.vue';
import DatePicker from "@/Components/Utilities/DatePicker.vue";
import Select from "@/Components/Utilities/Select.vue";
import VueSelect from "vue3-select-component";

export default {
    components: {
        VueSelect,
        Link,
        AuthNavigationBar,
        DatePicker,
        Select
    },
    data() {
        return {
            form: useForm({
                username: null,
                email: null,
                date_of_birth: null,
                first_name: null,
                last_name: null,
                sex_id: null,
                gender_id: null,
                country_id: null,
                city_id: null,
                sexuality_id: null,
                interests: [],
                password: null,
                confirm_password: null,
            }),
            countries: [],
            cities: [],
            sexes: [],
            genders: [],
            sexualities: [],
            interests: [],
        }
    },
    mounted() {
        this.fetchInitialData();
    },
    methods: {
        submit() {
            this.form.post(`/registration`, {
                onSuccess: () => {
                    this.form.reset();
                }
            });
        },
        async fetchInitialData() {
            await Promise.all([
                this.fetchData('/api/countries', 'countries'),
                this.fetchData('/api/genders', 'genders'),
                this.fetchData('/api/sexes', 'sexes'),
                this.fetchData('/api/sexualities', 'sexualities'),
                this.fetchData('/api/interests', 'interests'),
            ]);
        },
        async fetchData(url, stateKey) {
            try {
                const response = await axios.get(url);
                this[stateKey] = response.data;
            } catch (error) {
                console.error(`Error fetching ${stateKey}:`, error);
            }
        },
        async countrySelected() {
            this.form.city = null;

            await this.fetchData(`/api/cities/${this.form.country_id}`, 'cities');
        },
        countryDeselected() {
            this.country = null;
            this.city = null;
            this.cities = [];
        },
        onInterestRemoved(value) {
            this.form.interests = this.form.interests.filter((interest) => interest != value.id);
        }
    }
}
</script>
