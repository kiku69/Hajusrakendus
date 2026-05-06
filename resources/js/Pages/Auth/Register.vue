<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ name: '', email: '', password: '', password_confirmation: '' });

const submit = () => {
    form.post(route('register'), { onFinish: () => form.reset('password', 'password_confirmation') });
};
</script>

<template>
    <GuestLayout>
        <Head title="Registreeru" />

        <h1 style="font-family:'Georgia',serif; font-size:1.4rem; color:#e2ddd5; margin-bottom:1.75rem; text-align:center; letter-spacing:0.02em;">Loo konto</h1>

        <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:1.1rem;">
            <div>
                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Nimi</label>
                <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                <InputError class="mt-1" :message="form.errors.name" />
            </div>

            <div>
                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">E-post</label>
                <input id="email" type="email" v-model="form.email" required autocomplete="username"
                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div>
                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Parool</label>
                <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <div>
                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Kinnita parool</label>
                <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                <InputError class="mt-1" :message="form.errors.password_confirmation" />
            </div>

            <button type="submit" :disabled="form.processing"
                style="background:rgba(255,200,90,0.15); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:11px; border-radius:8px; font-size:0.85rem; letter-spacing:0.08em; cursor:pointer; transition:all 0.2s; margin-top:0.25rem;"
                onmouseover="this.style.background='rgba(255,200,90,0.25)'"
                onmouseout="this.style.background='rgba(255,200,90,0.15)'">
                Registreeru
            </button>
        </form>

        <p style="text-align:center; margin-top:1.5rem; font-size:0.82rem; color:#4a4640;">
            On juba konto?
            <Link :href="route('login')"
                style="color:#ffc85a; text-decoration:none; margin-left:4px;"
                onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                Logi sisse
            </Link>
        </p>
    </GuestLayout>
</template>