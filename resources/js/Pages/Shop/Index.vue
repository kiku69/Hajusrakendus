<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ products: Array });
const quantities = ref({});
props.products.forEach(p => { quantities.value[p.id] = 1; });

function addToCart(productId) {
    const form = useForm({ product_id: productId, quantity: quantities.value[productId] || 1 });
    form.post(route('cart.add'), { preserveScroll: true });
}
</script>

<template>
    <Head title="Pood" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-6">
                        <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                        <a :href="route('weather.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Ilm</a>
                        <a :href="route('map.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Kaart</a>
                        <a :href="route('blog.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Blogi</a>
                        <a :href="route('shop.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Pood</a>
                        <a :href="route('movies.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Filmid</a>
                    </div>
                    <a :href="route('cart.index')" style="position:relative; color:#8a8070; text-decoration:none;" onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#8a8070'">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px; height:22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                        </svg>
                        <span v-if="$page.props.cartCount > 0"
                            style="position:absolute; top:-8px; right:-8px; background:#ffc85a; color:#0d0d12; font-size:0.6rem; font-weight:700; border-radius:50%; width:16px; height:16px; display:flex; align-items:center; justify-content:center;">
                            {{ $page.props.cartCount }}
                        </span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div v-for="product in products" :key="product.id"
                    style="background:#16161f; border:1px solid rgba(255,200,90,0.08); border-radius:12px; overflow:hidden; transition:all 0.25s;"
                    onmouseover="this.style.borderColor='rgba(255,200,90,0.25)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.borderColor='rgba(255,200,90,0.08)'; this.style.transform='';">
                    <img :src="product.image" :alt="product.name" style="width:100%; height:200px; object-fit:cover;" />
                    <div style="padding:1.25rem;">
                        <h3 style="font-size:1rem; font-weight:600; color:#e2ddd5; margin-bottom:6px;">{{ product.name }}</h3>
                        <p style="color:#5a5650; font-size:0.82rem; line-height:1.5; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ product.description }}</p>
                        <p style="font-size:1.5rem; font-weight:700; color:#ffc85a; margin-top:12px; font-family:'Georgia',serif;">{{ product.price.toFixed(2) }} €</p>

                        <div style="display:flex; align-items:center; gap:10px; margin-top:14px;">
                            <label style="font-size:0.75rem; color:#6b6358; letter-spacing:0.08em;">Kogus</label>
                            <select v-model="quantities[product.id]"
                                style="background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:6px; padding:5px 10px; color:#e2ddd5; font-size:0.82rem; outline:none;">
                                <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
                            </select>
                        </div>

                        <button @click="addToCart(product.id)"
                            style="width:100%; margin-top:14px; background:rgba(255,200,90,0.12); border:1px solid rgba(255,200,90,0.25); color:#ffc85a; padding:10px; border-radius:8px; font-size:0.82rem; letter-spacing:0.06em; cursor:pointer; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(255,200,90,0.22)'"
                            onmouseout="this.style.background='rgba(255,200,90,0.12)'">
                            Lisa ostukorvi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>