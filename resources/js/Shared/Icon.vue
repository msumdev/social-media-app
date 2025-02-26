<template>
    <li
        class="icon icon-style"
        :style="{
            '--icon-background': background,
            '--icon-color': color,
        }"
    >
        <span class="tooltip" v-if="showTooltip">{{ tooltip }}</span>
        <i class="bi fs-2" :class="iconName"></i>
    </li>
</template>

<script>
export default {
    name: 'Icon',
    props: {
        tooltip: {
            type: String,
            required: true,
        },
		iconName: {
			type: String,
			required: true
		},
        background: {
            type: String,
            default: '#0d6efd',
        },
        color: {
            type: String,
            default: '#fff',
        },
        showTooltip: {
            type: Boolean,
            default: true,
        }
    },
};
</script>

<style scoped>
.night-mode .icon {
    background: #0f0f0f;
}

.icon {
    position: relative;
    background: #fff;
    color: #0d6efd;
    border-radius: 10%;
    margin-right: 10px;
    width: 50px;
    height: 50px;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.icon:hover .tooltip {
    top: -45px;
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.icon:hover span,
.icon:hover .tooltip {
    text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.1);
}

.tooltip {
    position: absolute;
    top: 0;
    font-size: 14px;
    background: #fff;
    color: #fff;
    padding: 5px 8px;
    border-radius: 5px;
    box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.tooltip::before {
    position: absolute;
    content: "";
    height: 8px;
    width: 8px;
    background: #fff;
    bottom: -3px;
    left: 50%;
    transform: translate(-50%) rotate(45deg);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.icon-style:hover,
.icon-style:hover .tooltip,
.icon-style:hover .tooltip::before {
    background: var(--icon-background);
    color: var(--icon-color);
}
</style>
