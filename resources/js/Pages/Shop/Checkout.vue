<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({ cartItems: Array, total: Number });

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    payment_method: 'stripe',
    stripe_token: '',
});

const paymentStatus = ref('idle');
const paymentError = ref('');
let stripe = null;
let card = null;
const stripeReady = ref(false);

onMounted(() => {
    const script = document.createElement('script');
    script.src = 'https://js.stripe.com/v3/';
    script.onload = () => {
        stripe = window.Stripe(import.meta.env.VITE_STRIPE_KEY);
        const elements = stripe.elements();
        card = elements.create('card', {
            style: {
                base: {
                    fontSize: '15px',
                    color: '#e2ddd5',
                    fontFamily: 'ui-sans-serif, system-ui, sans-serif',
                    '::placeholder': { color: '#6b6358' },
                    iconColor: '#ffc85a',
                },
                invalid: {
                    color: '#c05050',
                    iconColor: '#c05050',
                },
            },
        });
        card.mount('#stripe-card-element');
        card.on('ready', () => { stripeReady.value = true; });
        card.on('change', (e) => { paymentError.value = e.error ? e.error.message : ''; });
    };
    document.head.appendChild(script);
});

async function submit() {
    paymentError.value = '';
    paymentStatus.value = 'processing';
    if (form.payment_method === 'stripe') {
        if (!stripe || !card) {
            paymentError.value = 'Stripe pole laaditud.';
            paymentStatus.value = 'idle';
            return;
        }
        const { token, error } = await stripe.createToken(card, {
            name: form.first_name + ' ' + form.last_name,
            email: form.email,
        });
        if (error) {
            paymentError.value = error.message;
            paymentStatus.value = 'error';
            return;
        }
        form.stripe_token = token.id;
    }
    form.post(route('cart.pay'), {
        onSuccess: () => { paymentStatus.value = 'success'; },
        onError: (errors) => {
            paymentError.value = errors.payment || 'Makse ebaõnnestus.';
            paymentStatus.value = 'error';
        },
        onFinish: () => { if (paymentStatus.value === 'processing') paymentStatus.value = 'idle'; },
    });
}
</script>

<template>
    <Head title="Maksmine" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center space-x-6">
                    <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                    <a :href="route('shop.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Pood</a>
                    <a :href="route('cart.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Ostukorv</a>
                </div>
            </div>
        </nav>

        <!-- Processing overlay -->
        <div v-if="paymentStatus === 'processing'"
            style="position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:100; display:flex; align-items:center; justify-content:center;">
            <div style="background:#16161f; border:1px solid rgba(255,200,90,0.2); border-radius:16px; padding:2.5rem; text-align:center;">
                <div style="width:40px; height:40px; border:3px solid rgba(255,200,90,0.3); border-top-color:#ffc85a; border-radius:50%; animation:spin 0.8s linear infinite; margin:0 auto 1rem;"></div>
                <p style="color:#e2ddd5; font-size:1rem;">Töötlen makset...</p>
                <p style="color:#6b6358; font-size:0.8rem; margin-top:6px;">Palun ära sulge akent</p>
            </div>
        </div>

        <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <Link :href="route('cart.index')"
                style="color:#6b6358; font-size:0.8rem; text-decoration:none; letter-spacing:0.08em; display:inline-block; margin-bottom:2rem;"
                onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#6b6358'">
                Tagasi
            </Link>
            <h1 style="font-family:'Georgia',serif; font-size:2rem; color:#e2ddd5; margin-bottom:2rem;">Maksmine</h1>

            <div v-if="paymentError"
                style="background:rgba(180,60,60,0.1); border:1px solid rgba(180,60,60,0.3); color:#c05050; border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.5rem; font-size:0.85rem;">
                 {{ paymentError }}
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Order summary -->
                <div style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:14px; padding:1.75rem;">
                    <h2 style="font-size:1rem; font-weight:600; color:#c8b896; letter-spacing:0.04em; margin-bottom:1.25rem;">Tellimuse kokkuvõte</h2>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <div v-for="item in cartItems" :key="item.product.id"
                            style="display:flex; align-items:center; gap:12px;">
                            <img :src="item.product.image" :alt="item.product.name"
                                style="width:48px; height:48px; object-fit:cover; border-radius:6px; flex-shrink:0;" />
                            <div style="flex:1;">
                                <p style="font-size:0.85rem; color:#e2ddd5;">{{ item.product.name }}</p>
                                <p style="font-size:0.75rem; color:#4a4640;">{{ item.quantity }} tk</p>
                            </div>
                            <p style="font-size:0.85rem; font-weight:600; color:#c8b896;">{{ (item.product.price * item.quantity).toFixed(2) }} €</p>
                        </div>
                    </div>
                    <div style="margin-top:1.25rem; padding-top:1rem; border-top:1px solid rgba(255,200,90,0.1); display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#c8b896;">Kokku:</span>
                        <span style="font-family:'Georgia',serif; font-size:1.5rem; color:#ffc85a; font-weight:700;">{{ total.toFixed(2) }} €</span>
                    </div>
                    <div style="margin-top:1rem; background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:8px; padding:10px 14px; font-size:0.75rem; color:#4a4640; display:flex; align-items:center; gap:8px;">
                        Makse on kaitstud SSL krüpteerimisega.
                    </div>
                </div>

                <!-- Payment form -->
                <div style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:14px; padding:1.75rem;">
                    <h2 style="font-size:1rem; font-weight:600; color:#c8b896; letter-spacing:0.04em; margin-bottom:1.25rem;">Makseandmed</h2>

                    <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:1rem;">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:5px;">Eesnimi</label>
                                <input v-model="form.first_name" type="text" required
                                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.85rem; outline:none; box-sizing:border-box;"
                                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                                <p v-if="form.errors.first_name" style="color:#b04040; font-size:0.75rem; margin-top:3px;">{{ form.errors.first_name }}</p>
                            </div>
                            <div>
                                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:5px;">Perenimi</label>
                                <input v-model="form.last_name" type="text" required
                                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.85rem; outline:none; box-sizing:border-box;"
                                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                            </div>
                        </div>

                        <div>
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:5px;">E-post</label>
                            <input v-model="form.email" type="email" required
                                style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.85rem; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                        </div>

                        <div>
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:5px;">Telefon</label>
                            <input v-model="form.phone" type="tel" required
                                style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.85rem; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                        </div>

                        <div>
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:10px;">Makseviis</label>
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                <label :style="form.payment_method === 'stripe'
                                    ? 'display:flex; flex-direction:column; align-items:center; padding:14px; border:1px solid rgba(255,200,90,0.4); border-radius:10px; cursor:pointer; background:rgba(255,200,90,0.06);'
                                    : 'display:flex; flex-direction:column; align-items:center; padding:14px; border:1px solid rgba(255,255,255,0.08); border-radius:10px; cursor:pointer;'">
                                    <input type="radio" v-model="form.payment_method" value="stripe" style="display:none;" />
                                    <span style="font-size:1.5rem; margin-bottom:4px;"></span>
                                    <span style="font-size:0.8rem; color:#c8b896;">Pangakaart</span>
                                    <span style="font-size:0.7rem; color:#4a4640;">Stripe</span>
                                </label>
                                <label :style="form.payment_method === 'paypal'
                                    ? 'display:flex; flex-direction:column; align-items:center; padding:14px; border:1px solid rgba(255,200,90,0.4); border-radius:10px; cursor:pointer; background:rgba(255,200,90,0.06);'
                                    : 'display:flex; flex-direction:column; align-items:center; padding:14px; border:1px solid rgba(255,255,255,0.08); border-radius:10px; cursor:pointer;'">
                                    <input type="radio" v-model="form.payment_method" value="paypal" style="display:none;" />
                                    <span style="font-size:1.5rem; margin-bottom:4px;"></span>
                                    <span style="font-size:0.8rem; color:#c8b896;">PayPal</span>
                                    <span style="font-size:0.7rem; color:#4a4640;">Simulatsioon</span>
                                </label>
                            </div>
                        </div>

                        <div v-if="form.payment_method === 'stripe'">
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:5px;">Kaardi andmed</label>
                            <div id="stripe-card-element"
                                style="background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:11px 14px; min-height:42px;"></div>
                            
                            <div style="margin-top:10px; background:rgba(255,200,90,0.05); border:1px solid rgba(255,200,90,0.1); border-radius:8px; padding:10px 12px; font-size:0.72rem; color:#8a8070;">
                                <p style="font-weight:600; margin-bottom:4px;"> Test:</p>
                                <p>✅ <code style="background:rgba(255,200,90,0.1); padding:1px 6px; border-radius:4px;">4242 4242 4242 4242</code></p>
                                <p style="margin-top:2px;">❌ <code style="background:rgba(255,200,90,0.1); padding:1px 6px; border-radius:4px;">4000 0000 0000 0002</code></p>
                                <p style="margin-top:4px; color:#4a4640;">Kuupäev: suvaline tulevik · CVC: 3 numbrit</p>
                            </div>
                        </div>

                        

                        <button type="submit" :disabled="form.processing || paymentStatus === 'processing'"
                            style="background:rgba(80,180,90,0.15); border:1px solid rgba(80,180,90,0.35); color:#5ab86a; padding:12px; border-radius:8px; font-size:0.85rem; letter-spacing:0.08em; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; gap:8px;"
                            onmouseover="this.style.background='rgba(80,180,90,0.25)'"
                            onmouseout="this.style.background='rgba(80,180,90,0.15)'">
                             Maksa {{ total.toFixed(2) }} €
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>