<template>
    <div class="min-h-screen flex flex-col items-center justify-center m-4 sm:m-0">
        <AuthNavigationBar></AuthNavigationBar>

        <portal-target name="header"></portal-target>

        <div class="w-full max-w-xl flex bg-white rounded-lg p-4 m-8 shadow-lg dark:bg-gray-900">
            <div class="container mx-auto">
                <div class="success-alert" role="alert" v-if="$page.props.success">
                    {{ $page.props.success }}
                </div>

                <div class="danger-alert" role="alert" v-if="$page.props.error">
                    {{ $page.props.error }}
                </div>

                <slot />
            </div>
        </div>
        <h2 class="text-md text-gray-400">
            <portal-target name="footer"></portal-target>
        </h2>
    </div>
</template>

<script>
import AuthNavigationBar from "@/Components/Navigation/AuthNavigationBar.vue";
import {Link} from "@inertiajs/vue3";

export default {
    components: {
        Link,
        AuthNavigationBar
    },
    data() {
        return {}
    },
}
</script>
