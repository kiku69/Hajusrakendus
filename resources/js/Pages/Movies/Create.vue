<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    director: '',
    release_year: new Date().getFullYear(),
    genre: '',
    rating: '',
    image: null,
});

function submit() {
    form.post(route('movies.store'), { forceFormData: true });
}
</script>

<template>
    <Head title="Lisa uus" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center space-x-6">
                    <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                    <a :href="route('movies.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Filmid</a>
                </div>
            </div>
        </nav>

        <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <Link :href="route('movies.index')"
                style="color:#6b6358; font-size:0.8rem; text-decoration:none; letter-spacing:0.08em; display:inline-block; margin-bottom:2rem;"
                onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#6b6358'">
                ← Tagasi filmide juurde
            </Link>

            <div style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:14px; padding:2rem;">
                <h1 style="font-family:'Georgia',serif; font-size:1.5rem; color:#e2ddd5; margin-bottom:1.75rem;">Lisa uus film</h1>

                <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:1.25rem;">
                    <div>
                        <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Pealkiri *</label>
                        <input v-model="form.title" type="text" required
                            style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                            onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                            onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                        <div v-if="form.errors.title" style="color:#b04040; font-size:0.78rem; margin-top:4px;">{{ form.errors.title }}</div>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Kirjeldus *</label>
                        <textarea v-model="form.description" rows="5" required
                            style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; resize:vertical; box-sizing:border-box;"
                            onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                            onblur="this.style.borderColor='rgba(255,200,90,0.15)'"></textarea>
                        <div v-if="form.errors.description" style="color:#b04040; font-size:0.78rem; margin-top:4px;">{{ form.errors.description }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Režissöör *</label>
                            <input v-model="form.director" type="text" required
                                style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                            <div v-if="form.errors.director" style="color:#b04040; font-size:0.78rem; margin-top:4px;">{{ form.errors.director }}</div>
                        </div>
                        <div>
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Aasta *</label>
                            <input v-model="form.release_year" type="number" min="1888" max="2030" required
                                style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Žanr</label>
                            <input v-model="form.genre" type="text" placeholder="nt Action, Drama..."
                                style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                        </div>
                        <div>
                            <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Hinnang (0–10)</label>
                            <input v-model="form.rating" type="number" min="0" max="10" step="0.1"
                                style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.9rem; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                        </div>
                    </div>

                    <div>
                        <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.14em; text-transform:uppercase; margin-bottom:6px;">Pilt</label>
                        <input type="file" accept="image/*" @input="form.image = $event.target.files[0]"
                            style="width:100%; font-size:0.82rem; color:#8a8070;" />
                        <div v-if="form.errors.image" style="color:#b04040; font-size:0.78rem; margin-top:4px;">{{ form.errors.image }}</div>
                    </div>

                    <button type="submit" :disabled="form.processing"
                        style="background:rgba(255,200,90,0.15); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:11px 24px; border-radius:8px; font-size:0.85rem; letter-spacing:0.08em; cursor:pointer; transition:all 0.2s; align-self:flex-start;"
                        onmouseover="this.style.background='rgba(255,200,90,0.25)'"
                        onmouseout="this.style.background='rgba(255,200,90,0.15)'">
                        Lisa uss
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>