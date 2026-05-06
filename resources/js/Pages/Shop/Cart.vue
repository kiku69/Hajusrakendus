<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ cartItems: Array });
const total = computed(() => props.cartItems.reduce((sum, item) => sum + item.product.price * item.quantity, 0));

function updateQuantity(productId, quantity) {
    router.patch(route('cart.update'), { product_id: productId, quantity });
}
function removeItem(productId) {
    router.delete(route('cart.remove'), { data: { product_id: productId } });
}
</script>

<template>
    <Head title="Ostukorv" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center space-x-6">
                    <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                    <a :href="route('weather.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Ilm</a>
                    <a :href="route('map.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Kaart</a>
                    <a :href="route('blog.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Blogi</a>
                    <a :href="route('shop.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Pood</a>
                    <a :href="route('movies.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Filmid</a>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <Link :href="route('shop.index')"
                style="color:#6b6358; font-size:0.8rem; text-decoration:none; letter-spacing:0.08em; display:inline-block; margin-bottom:2rem;"
                onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#6b6358'">
                ← Tagasi poodi
            </Link>

            <h1 style="font-family:'Georgia',serif; font-size:2rem; color:#e2ddd5; margin-bottom:2rem;">Ostukorv</h1>

            <div v-if="cartItems.length === 0" style="text-align:center; padding:5rem 0; color:#4a4640;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:48px; height:48px; margin:0 auto 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
                <p style="font-size:1rem;">Ostukorv on tühi.</p>
                <Link :href="route('shop.index')" style="color:#ffc85a; text-decoration:none; font-size:0.85rem; display:inline-block; margin-top:12px;">
                    Mine poodi →
                </Link>
            </div>

            <div v-else>
                <div style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:14px; overflow:hidden;">
                    <div v-for="item in cartItems" :key="item.product.id"
                        style="display:flex; align-items:center; padding:1.1rem 1.25rem; border-bottom:1px solid rgba(255,200,90,0.06);">
                        <img :src="item.product.image" :alt="item.product.name"
                            style="width:64px; height:64px; object-fit:cover; border-radius:8px; flex-shrink:0;" />
                        <div style="flex:1; margin-left:1rem;">
                            <h3 style="font-size:0.9rem; font-weight:600; color:#e2ddd5;">{{ item.product.name }}</h3>
                            <p style="color:#ffc85a; font-size:0.85rem; font-weight:600; margin-top:3px;">{{ item.product.price.toFixed(2) }} €</p>
                        </div>
                        <div style="display:flex; align-items:center; gap:14px;">
                            <select :value="item.quantity"
                                @change="updateQuantity(item.product.id, parseInt($event.target.value))"
                                style="background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:6px; padding:5px 10px; color:#e2ddd5; font-size:0.82rem; outline:none;">
                                <option v-for="n in 99" :key="n" :value="n">{{ n }}</option>
                            </select>
                            <p style="font-size:0.9rem; font-weight:600; color:#c8b896; min-width:70px; text-align:right;">
                                {{ (item.product.price * item.quantity).toFixed(2) }} €
                            </p>
                            <button @click="removeItem(item.product.id)"
                                style="color:#4a4640; background:none; border:none; cursor:pointer; padding:4px;"
                                onmouseover="this.style.color='#c05050'" onmouseout="this.style.color='#4a4640'">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px; height:18px;" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:14px; padding:1.5rem; margin-top:1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:1rem; color:#c8b896;">Kokku:</span>
                        <span style="font-family:'Georgia',serif; font-size:1.6rem; color:#ffc85a; font-weight:700;">{{ total.toFixed(2) }} €</span>
                    </div>
                    <Link :href="route('cart.checkout')"
                        style="display:block; width:100%; margin-top:1rem; background:rgba(255,200,90,0.15); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:12px; border-radius:8px; text-align:center; font-size:0.85rem; letter-spacing:0.08em; text-decoration:none; transition:all 0.2s; box-sizing:border-box;"
                        onmouseover="this.style.background='rgba(255,200,90,0.25)'"
                        onmouseout="this.style.background='rgba(255,200,90,0.15)'">
                        Osta kätte →
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>