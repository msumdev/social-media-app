<template>
    <portal to="header">
        <h1 class="text-4xl font-bold text-center text-blue-500 mb-4 mt-8">
            Password Recovery
        </h1>
        <h2 class="text-xl text-center text-gray-800 mb-8 dark:text-gray-400">
            Confirm your new password, make sure it's strong!
        </h2>
    </portal>

    <form @submit.prevent="submit()">
        <div class="mb-5">
            <label for="password" class="block mb-2 text-md text-blue-600 dark:text-white">Password</label>
            <input type="password" id="password" class="primary-input" placeholder="Enter your password" v-model="form.password" />
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.password">{{ form.errors.password }}</div>
        </div>
        <div class="mb-5">
            <label for="confirmPassword" class="block mb-2 text-md text-blue-600 dark:text-white">Confirm Password</label>
            <input type="password" id="confirmPassword" class="primary-input" placeholder="Enter your password" v-model="form.confirm_password" />
            <div class="text-red-600 text-sm mt-2" v-if="form.errors.confirm_password">{{ form.errors.confirm_password }}</div>
        </div>
        <button type="submit" class="primary-button w-full">
            Save
        </button>
    </form>
</template>

<script>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3';
import AuthNavigationBar from '@/Components/Navigation/AuthNavigationBar.vue';

export default {
    components: {
        Link,
        AuthNavigationBar,
    },
    data() {
        return {
            form: useForm({
                password: null,
                confirm_password: null,
            })
        }
    },
    methods: {
        submit() {
            this.form.post(`/forgotten-password/${this.$page.props.token}`, {
                onSuccess: () => {
                    this.form.reset();
                }
            });
        }
    }
}
</script>
