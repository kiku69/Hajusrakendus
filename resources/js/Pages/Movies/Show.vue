<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({ movie: Object });

function deleteMovie() {
    if (confirm('Kas oled kindel, et soovid selle filmi kustutada?')) {
        router.delete(route('movies.destroy', props.movie.id));
    }
}
</script>

<template>
    <Head :title="movie.title" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-6">
                        <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                        <a :href="route('weather.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Ilm</a>
                        <a :href="route('map.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Kaart</a>
                        <a :href="route('blog.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Blogi</a>
                        <a :href="route('shop.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Pood</a>
                        <a :href="route('movies.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Filmid</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <Link :href="route('movies.index')"
                style="color:#6b6358; font-size:0.8rem; text-decoration:none; letter-spacing:0.08em; display:inline-block; margin-bottom:2rem;"
                onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#6b6358'">
                Tagasi
            </Link>

            <div style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:14px; overflow:hidden;">
                <div v-if="movie.image" style="position:relative;">
                    <img :src="movie.image.startsWith('http') ? movie.image : '/storage/' + movie.image"
                        :alt="movie.title"
                        style="width:100%; height:320px; object-fit:cover;" />
                    <div style="position:absolute; bottom:0; left:0; right:0; height:120px; background:linear-gradient(to top, #16161f, transparent);"></div>
                </div>

                <div style="padding:2rem;">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 style="font-family:'Georgia',serif; font-size:1.9rem; font-weight:700; color:#e2ddd5; line-height:1.2;">{{ movie.title }}</h1>
                            <div style="display:flex; align-items:center; gap:12px; margin-top:10px; flex-wrap:wrap;">
                                <span style="color:#8a8070; font-size:0.85rem;">{{ movie.director }}</span>
                                <span style="color:#4a4640;">·</span>
                                <span style="color:#8a8070; font-size:0.85rem;">{{ movie.release_year }}</span>
                                <span v-if="movie.genre"
                                    style="background:rgba(255,200,90,0.1); color:#a88840; font-size:0.7rem; padding:3px 12px; border-radius:20px; letter-spacing:0.08em;">
                                    {{ movie.genre }}
                                </span>
                            </div>
                        </div>
                        <div v-if="movie.rating"
                            style="text-align:center; background:rgba(255,200,90,0.08); border:1px solid rgba(255,200,90,0.2); border-radius:10px; padding:12px 18px; flex-shrink:0;">
                            <div style="font-size:1.6rem; font-weight:700; color:#ffc85a;">{{ movie.rating }}</div>
                            <div style="font-size:0.65rem; color:#6b6358; letter-spacing:0.12em;">/10</div>
                        </div>
                    </div>

                    <div style="margin-top:1.75rem; color:#a8a098; line-height:1.75; font-size:0.9rem; white-space:pre-wrap;">{{ movie.description }}</div>

                    <div style="margin-top:1.5rem; padding-top:1rem; border-top:1px solid rgba(255,200,90,0.08); display:flex; align-items:center; gap:12px;">
                        <span style="font-size:0.75rem; color:#4a4640;">Lisas: {{ movie.user?.name || 'Tundmatu' }}</span>
                        <span style="color:#2a2a30;">·</span>
                        <span style="font-size:0.75rem; color:#4a4640;">{{ new Date(movie.created_at).toLocaleDateString('et-EE') }}</span>
                    </div>

                    <div v-if="$page.props.auth.user?.id === movie.user_id"
                        style="display:flex; gap:10px; margin-top:1.5rem;">
                        <Link :href="route('movies.edit', movie.id)"
                            style="background:rgba(255,200,90,0.12); border:1px solid rgba(255,200,90,0.25); color:#ffc85a; padding:8px 20px; border-radius:8px; font-size:0.8rem; letter-spacing:0.05em; text-decoration:none;"
                            onmouseover="this.style.background='rgba(255,200,90,0.22)'"
                            onmouseout="this.style.background='rgba(255,200,90,0.12)'">
                            Muuda
                        </Link>
                        <button @click="deleteMovie"
                            style="background:rgba(180,60,60,0.1); border:1px solid rgba(180,60,60,0.25); color:#b04040; padding:8px 20px; border-radius:8px; font-size:0.8rem; letter-spacing:0.05em; cursor:pointer;"
                            onmouseover="this.style.background='rgba(180,60,60,0.2)'"
                            onmouseout="this.style.background='rgba(180,60,60,0.1)'">
                            Kustuta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>