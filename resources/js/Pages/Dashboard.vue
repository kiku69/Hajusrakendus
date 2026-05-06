<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 style="font-family:'Georgia',serif; font-size:1.1rem; font-weight:600; color:#c8b896; letter-spacing:0.05em;">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                    <Link :href="route('weather.index')"
                        >
                        <h4 style="font-size:1rem; font-weight:600; color:#e2ddd5; margin-bottom:0.35rem;">Ilmateade</h4>
                        <p style="font-size:0.8rem; color:#6b6358;">Vaata ilmaennustust erinevate linnade kohta</p>
                        <div style="position:absolute; bottom:0; left:0; right:0; height:2px; background:linear-gradient(90deg, transparent, rgba(90,160,255,0.5), transparent);"></div>
                    </Link>

                    <Link :href="route('map.index')"
                        >
                        <h4 style="font-size:1rem; font-weight:600; color:#e2ddd5; margin-bottom:0.35rem;">Kaardirakendus</h4>
                        <p style="font-size:0.8rem; color:#6b6358;">Lisa ja halda markereid kaardil</p>
                        <div style="position:absolute; bottom:0; left:0; right:0; height:2px; background:linear-gradient(90deg, transparent, rgba(90,200,120,0.5), transparent);"></div>
                    </Link>

                    <Link :href="route('blog.index')"
                        >
                        <h4 style="font-size:1rem; font-weight:600; color:#e2ddd5; margin-bottom:0.35rem;">Blogi</h4>
                        <p style="font-size:0.8rem; color:#6b6358;">Loe ja kirjuta blogipostitusi</p>
                        <div style="position:absolute; bottom:0; left:0; right:0; height:2px; background:linear-gradient(90deg, transparent, rgba(180,100,255,0.5), transparent);"></div>
                    </Link>

                    <Link :href="route('shop.index')"
                        >
                        <h4 style="font-size:1rem; font-weight:600; color:#e2ddd5; margin-bottom:0.35rem;">E-pood</h4>
                        <p style="font-size:0.8rem; color:#6b6358;">Sirvi tooteid ja tee tellimusi</p>
                        <div style="position:absolute; bottom:0; left:0; right:0; height:2px; background:linear-gradient(90deg, transparent, rgba(255,160,60,0.5), transparent);"></div>
                    </Link>

                    <Link :href="route('movies.index')"
                        >
                        <h4 style="font-size:1rem; font-weight:600; color:#e2ddd5; margin-bottom:0.35rem;">Filmid</h4>
                        <p style="font-size:0.8rem; color:#6b6358;">Lisa ja sirvi filme, kasuta API-t</p>
                        <div style="position:absolute; bottom:0; left:0; right:0; height:2px; background:linear-gradient(90deg, transparent, rgba(255,80,80,0.5), transparent);"></div>
                    </Link>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>