<template>
    <portal to="header">
        <h1 class="text-4xl font-bold text-center text-blue-500 mb-4 mt-8">
            Password Recovery
        </h1>
        <h2 class="text-xl text-center text-gray-800 mb-8 dark:text-gray-400">
            We'll email you a link so you can reset your password
        </h2>
    </portal>

    <form @submit.prevent="submit()">
        <div class="mb-5">
            <label for="email" class="block mb-2 text-md text-blue-600 dark:text-white">Email</label>
            <input type="text" id="email" class="primary-input" placeholder="Enter your email" v-model="form.email" />
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.email">{{ form.errors.email }}</div>
        </div>
        <div class="mb-5">
            <label for="date-of-birth" class="block mb-2 text-md text-blue-600 dark:text-white">Date of Birth</label>
            <DatePicker id="date-of-birth" v-model="form.date_of_birth"></DatePicker>
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.date_of_birth">{{ form.errors.date_of_birth }}</div>
        </div>
        <button type="submit" class="primary-button w-full">
            Reset Password
        </button>
    </form>

    <portal to="footer">
        Already have an account?
        <Link href="/login" class="default-link">Login</Link>
    </portal>
</template>

<script>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3';
import AuthNavigationBar from '@/Components/Navigation/AuthNavigationBar.vue';
import DatePicker from "@/Components/Utilities/DatePicker.vue";

export default {
    components: {
        Link,
        DatePicker,
        AuthNavigationBar,
    },
    data() {
        return {
            form: useForm({
                email: null,
                date_of_birth: null,
            })
        }
    },
    methods: {
        submit() {
            this.form.post(`/forgotten-password`, {
                onSuccess: () => {
                    this.form.reset();
                }
            });
        }
    }
}
</script>
