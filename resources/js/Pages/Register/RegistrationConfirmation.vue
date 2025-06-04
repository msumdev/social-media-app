<template>
    <div class="min-h-screen flex flex-col items-center justify-center m-4 sm:m-0">
        <AuthNavigationBar></AuthNavigationBar>

        <h1 class="text-4xl font-bold text-center text-blue-500 mb-4 mt-8">
            Uh oh! What's happened?
        </h1>
        <h2 class="text-xl text-center text-gray-800 mb-8 dark:text-gray-400">
            There was an issue with your confirmation link, I've sent you another!
        </h2>
        <div class="w-full max-w-xl flex bg-white rounded-lg p-4 shadow-lg dark:bg-gray-900">
            <div class="container mx-auto">
                <div class="success-alert" role="alert" v-if="$page.props.success">
                    {{ $page.props.success }}
                </div>
                <div class="danger-alert" role="alert" v-if="$page.props.error">
                    {{ $page.props.error }}
                </div>

                <form @submit.prevent="submit()">
                </form>
            </div>
        </div>
        <h2 class="text-md text-gray-400 m-8">
            <span class="text-dark-grey">Already have an account? </span>
            <Link href="/login" class="default-link">Log in</Link>
        </h2>
    </div>
</template>

<script>
import {Link, useForm} from '@inertiajs/vue3'
import AuthNavigationBar from '@/Components/Navigation/AuthNavigationBar.vue';

export default {
    components: {
        Link,
        AuthNavigationBar,
    },
    data() {
        return {
            form: useForm({
            }),
        }
    },
    methods: {
        submit() {
            this.form.post(`/registration`, {
                onSuccess: () => {
                    this.form.reset();
                }
            });
        },
    }
}
</script>
