<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: { type: String, required: true },
    active: { type: Boolean },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-3 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out'
);
</script>

<template>
    <Link :href="href" :class="classes"
        :style="active
            ? 'border-color:#ffc85a; color:#ffc85a; letter-spacing:0.04em;'
            : 'border-color:transparent; color:#8a8070; letter-spacing:0.04em;'"
        onmouseover="if(!this.style.borderColor.includes('ffc85a')) { this.style.color='#c8b896'; }"
        onmouseout="if(!this.style.borderColor.includes('ffc85a')) { this.style.color='#8a8070'; }">
        <slot />
    </Link>
</template>