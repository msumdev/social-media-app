<template>
    <portal to="header">
        <h1 class="text-4xl font-bold text-center text-blue-500 mb-4 mt-8">
            Login into your account
        </h1>
        <h2 class="text-xl text-center text-gray-800 mb-8 dark:text-gray-400">
            Welcome back! Please enter your details.
        </h2>
    </portal>

    <form @submit.prevent="submit()">
        <div class="mb-5">
            <label for="email" class="block mb-2 text-md text-blue-600 dark:text-white">Email</label>
            <input type="email" id="email" class="primary-input" placeholder="Enter your email" v-model="form.email" />
        </div>
        <div class="mb-5">
            <label for="password" class="block mb-2 text-md text-blue-600 dark:text-white">Password</label>
            <input type="password" id="password" class="primary-input" placeholder="Enter your password" v-model="form.password" />
        </div>
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center h-5">
                <input id="remember" type="checkbox" class="checkbox cursor-pointer accent-violet-900 dark:accent-gray-600" />
                <label for="remember" class="ms-2 text-sm text-gray-900 dark:text-gray-300 cursor-pointer">Keep me logged in</label>
            </div>
            <Link href="/forgotten-password" class="default-link">Reset Password</Link>
        </div>
        <button type="submit" class="primary-button w-full">
            Log me in
        </button>
    </form>

    <portal to="footer">
        Don’t have an account?
        <Link href="/registration" class="default-link">Register</Link>
    </portal>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3'
import { UserStore } from '@/Stores/userStore';
import UnauthenticatedLayout from "@/Layouts/UnauthenticatedLayout.vue";

export default {
    components: {
        Link,
    },
    data() {
        return {
            form: useForm({
                email: 'test-user@test.com',
                password: '&BrefT2D3UopN$s$',
            }),
            userStore: UserStore(),
        }
    },
    methods: {
        submit() {
            this.form.post(`/login`, {
                onSuccess: () => {
                    (async () => {
                        await this.userStore.getCurrentUser();
                    })();
                }
            });
        }
    }
}
</script>
