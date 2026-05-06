<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({ canResetPassword: Boolean, status: String });

const form = useForm({ email: '', password: '', remember: false });

const submit = () => {
    form.post(route('login'), { onFinish: () => form.reset('password') });
};
</script>

<template>
    <GuestLayout>
        <Head title="Logi sisse" />

        <h1 style="font-family:'Georgia',serif; font-size:1.4rem; color:#e2ddd5; margin-bottom:1.75rem; text-align:center; letter-spacing:0.02em;">Logi sisse</h1>

        <div v-if="status" style="background:rgba(80,180,90,0.1); border:1px solid rgba(80,180,90,0.25); color:#5ab86a; border-radius:8px; padding:10px 14px; font-size:0.82rem; margin-bottom:1.25rem;">
            {{ status }}
        </div>

        <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:1.1rem;">
            <div>
                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">E-post</label>
                <input id="email" type="email" v-model="form.email" required autofocus autocomplete="username"
                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                <InputError class="mt-1" :message="form.errors.email" />
            </div>

            <div>
                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Parool</label>
                <input id="password" type="password" v-model="form.password" required autocomplete="current-password"
                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                <InputError class="mt-1" :message="form.errors.password" />
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span style="font-size:0.82rem; color:#6b6358;">Jäta mind meelde</span>
                </label>
                <Link v-if="canResetPassword" :href="route('password.request')"
                    style="font-size:0.8rem; color:#6b6358; text-decoration:none; letter-spacing:0.03em;"
                    onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#6b6358'">
                    Unustasid parooli?
                </Link>
            </div>

            <button type="submit" :disabled="form.processing"
                style="background:rgba(255,200,90,0.15); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:11px; border-radius:8px; font-size:0.85rem; letter-spacing:0.08em; cursor:pointer; transition:all 0.2s; margin-top:0.25rem;"
                onmouseover="this.style.background='rgba(255,200,90,0.25)'"
                onmouseout="this.style.background='rgba(255,200,90,0.15)'">
                Logi sisse
            </button>
        </form>

        <p style="text-align:center; margin-top:1.5rem; font-size:0.82rem; color:#4a4640;">
            Pole kontot?
            <Link :href="route('register')"
                style="color:#ffc85a; text-decoration:none; margin-left:4px;"
                onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                Registreeru
            </Link>
        </p>
    </GuestLayout>
</template>