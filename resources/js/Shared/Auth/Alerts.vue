<template>
    <div v-if="$page.props.error" class="alert alert-danger text-center" role="alert">
        {{ $page.props.error }}
    </div>

    <div v-if="$page.props.success" class="alert alert-success text-center" role="alert">
        {{ $page.props.success }}
    </div>
</template>
<script>
export default {}
</script>
