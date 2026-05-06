<script setup>
import { Head, Link } from '@inertiajs/vue3';
const props = defineProps({ order: Object });
</script>

<template>
    <Head title="Tellimus kinnitatud" />

    <div class="min-h-screen flex items-center justify-center px-4" style="background:#0d0d12;">
        <div style="background:#16161f; border:1px solid rgba(255,200,90,0.15); border-radius:16px; max-width:500px; width:100%; padding:2.5rem; text-align:center;">
            <div style="font-size:3rem; margin-bottom:1rem;">✓</div>
            <h1 style="font-family:'Georgia',serif; font-size:1.6rem; color:#e2ddd5; margin-bottom:8px;">Makse õnnestus!</h1>
            <p style="color:#6b6358; font-size:0.85rem; line-height:1.6; margin-bottom:1.75rem;">
                Täname tellimuse eest, <strong style="color:#c8b896;">{{ order.first_name }}</strong>!
                Kinnitus saadetakse aadressile <strong style="color:#c8b896;">{{ order.email }}</strong>.
            </p>

            <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.1); border-radius:10px; padding:1.1rem; text-align:left; margin-bottom:1.5rem;">
                <div style="display:flex; justify-content:space-between; padding:5px 0; font-size:0.82rem;">
                    <span style="color:#6b6358;">Tellimuse nr</span>
                    <span style="font-family:monospace; color:#c8b896;">#{{ order.id }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:5px 0; font-size:0.82rem;">
                    <span style="color:#6b6358;">Makse ID</span>
                    <span style="font-family:monospace; font-size:0.72rem; color:#8a8070;">{{ order.payment_id }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:5px 0; font-size:0.82rem;">
                    <span style="color:#6b6358;">Makseviis</span>
                    <span style="color:#c8b896; text-transform:capitalize;">{{ order.payment_method }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:10px 0 5px; font-size:0.9rem; border-top:1px solid rgba(255,200,90,0.08); margin-top:5px;">
                    <span style="color:#e2ddd5; font-weight:600;">Kokku makstud</span>
                    <span style="color:#ffc85a; font-weight:700; font-family:'Georgia',serif;">{{ order.total.toFixed(2) }} €</span>
                </div>
            </div>

            <div style="text-align:left; margin-bottom:1.75rem;">
                <p style="font-size:0.75rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:10px;">Tellitud tooted</p>
                <div v-for="item in order.items" :key="item.id"
                    style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid rgba(255,200,90,0.06);">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img :src="item.product.image" :alt="item.product.name"
                            style="width:36px; height:36px; object-fit:cover; border-radius:6px;" />
                        <div>
                            <p style="font-size:0.82rem; color:#e2ddd5; font-weight:500;">{{ item.product.name }}</p>
                            <p style="font-size:0.72rem; color:#4a4640;">{{ item.quantity }} tk</p>
                        </div>
                    </div>
                    <p style="font-size:0.82rem; color:#c8b896; font-weight:600;">{{ (item.price * item.quantity).toFixed(2) }} €</p>
                </div>
            </div>

            <Link :href="route('shop.index')"
                style="display:block; background:rgba(255,200,90,0.12); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:11px; border-radius:8px; font-size:0.82rem; letter-spacing:0.08em; text-decoration:none;"
                onmouseover="this.style.background='rgba(255,200,90,0.22)'"
                onmouseout="this.style.background='rgba(255,200,90,0.12)'">
                Tagasi poodi →
            </Link>
        </div>
    </div>
</template>