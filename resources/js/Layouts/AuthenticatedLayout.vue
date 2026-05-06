<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
            <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,198,90,0.12); backdrop-filter:blur(16px); position:sticky; top:0; z-index:50;">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')" class="flex items-center gap-2">
                                    <span style="font-family:'Georgia',serif; font-size:1rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a;">HAJUS</span>
                                   
                                </Link>
                            </div>
                            <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                                <!-- <NavLink :href="route('weather.index')" :active="route().current('weather.index')">Ilm</NavLink> -->
                                <NavLink :href="route('map.index')" :active="route().current('map.index')">Kaart</NavLink>
                                <NavLink :href="route('blog.index')" :active="route().current('blog.*')">Blogi</NavLink>
                                <NavLink :href="route('shop.index')" :active="route().current('shop.*')">Pood</NavLink>
                                <NavLink :href="route('movies.index')" :active="route().current('movies.*')">Filmid</NavLink>
                            </div>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                style="background:rgba(255,200,90,0.08); border:1px solid rgba(255,200,90,0.2); color:#c8b896; border-radius:8px; padding:6px 14px; font-size:0.8rem; letter-spacing:0.04em; cursor:pointer; transition:all 0.2s;"
                                                onmouseover="this.style.background='rgba(255,200,90,0.16)'"
                                                onmouseout="this.style.background='rgba(255,200,90,0.08)'">
                                                {{ $page.props.auth.user.name }}
                                                <svg class="ms-2 -me-0.5 h-3 w-3 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">Profiil</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">Logi välja</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                style="color:#8a8070; padding:8px; border-radius:8px;">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden" style="border-top:1px solid rgba(255,200,90,0.1);">
                    <div class="pt-2 pb-3 space-y-1 px-4">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                        <!-- <ResponsiveNavLink :href="route('weather.index')" :active="route().current('weather.index')">Ilm</ResponsiveNavLink> -->
                        <ResponsiveNavLink :href="route('map.index')" :active="route().current('map.index')">Kaart</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('blog.index')" :active="route().current('blog.*')">Blogi</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('shop.index')" :active="route().current('shop.*')">Pood</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('movies.index')" :active="route().current('movies.*')">Filmid</ResponsiveNavLink>
                    </div>
                    <div class="pt-4 pb-1 px-4" style="border-top:1px solid rgba(255,200,90,0.1);">
                        <div style="color:#c8b896; font-size:0.9rem;">{{ $page.props.auth.user.name }}</div>
                        <div style="color:#6b6358; font-size:0.8rem;">{{ $page.props.auth.user.email }}</div>
                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Profiil</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Logi välja</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <header v-if="$slots.header" style="background:rgba(255,200,90,0.04); border-bottom:1px solid rgba(255,200,90,0.1);">
                <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>