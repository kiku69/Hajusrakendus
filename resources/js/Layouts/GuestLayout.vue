<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0"
        style="background:#0d0d12;">
        <div class="mb-6">
            <Link href="/" style="font-family:'Georgia',serif; font-size:1.5rem; font-weight:700; letter-spacing:0.12em; color:#ffc85a; text-decoration:none;">
                HAJUS
            </Link>
        </div>
        <div class="w-full sm:max-w-md px-8 py-8 overflow-hidden sm:rounded-xl"
            style="background:#16161f; border:1px solid rgba(255,200,90,0.12); box-shadow:0 25px 60px rgba(0,0,0,0.5);">
            <slot />
        </div>
    </div>
</template>