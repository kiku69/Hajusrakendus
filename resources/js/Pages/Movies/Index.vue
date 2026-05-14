<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    movies: Array,
    genres: Array,
    filters: Object,
});

const activeSource = ref('mine');
const friendMovies = ref([]);
const friendLoading = ref(false);
const friendError = ref(null);
const selectedRecipe = ref(null);

const FRIEND_API = 'https://raamistikud.ta24haller.itmajakas.ee/api/recipes';

async function fetchFriendMovies() {
    friendLoading.value = true;
    friendError.value = null;
    try {
        const res = await fetch(FRIEND_API);
        const json = await res.json();
        friendMovies.value = json.data ?? json;
    } catch (e) {
        friendError.value = 'Ei suutnud sõbra APIt laadida.';
    } finally {
        friendLoading.value = false;
    }
}

function switchSource(src) {
    activeSource.value = src;
    if (src === 'friend' && friendMovies.value.length === 0) {
        fetchFriendMovies();
    }
}

const displayedMovies = computed(() =>
    activeSource.value === 'mine' ? props.movies : friendMovies.value
);

const search = ref(props.filters?.search || '');
const genre = ref(props.filters?.genre || '');
const sort = ref(props.filters?.sort || 'created_at');
const direction = ref(props.filters?.direction || 'desc');

function applyFilters() {
    router.get(route('movies.index'), {
        search: search.value || undefined,
        genre: genre.value || undefined,
        sort: sort.value,
        direction: direction.value,
    }, { preserveState: true });
}

function clearFilters() {
    search.value = '';
    genre.value = '';
    sort.value = 'created_at';
    direction.value = 'desc';
    router.get(route('movies.index'));
}

function deleteMovie(id) {
    if (confirm('Kas oled kindel, et soovid selle filmi kustutada?')) {
        router.delete(route('movies.destroy', id));
    }
}

function getImageSrc(movie) {
    if (!movie.image) return null;
    if (movie.image.startsWith('http')) return movie.image;
    return '/storage/' + movie.image;
}

function openRecipe(item) {
    selectedRecipe.value = item;
}

function closeRecipe() {
    selectedRecipe.value = null;
}
</script>

<template>
    <Head title="Filmid" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <!-- Nav -->
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-6">
                        <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                        <a :href="route('weather.index')" style="color:#6b6358; font-size:0.85rem; letter-spacing:0.04em; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Ilm</a>
                        <a :href="route('map.index')" style="color:#6b6358; font-size:0.85rem; letter-spacing:0.04em; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Kaart</a>
                        <a :href="route('blog.index')" style="color:#6b6358; font-size:0.85rem; letter-spacing:0.04em; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Blogi</a>
                        <a :href="route('shop.index')" style="color:#6b6358; font-size:0.85rem; letter-spacing:0.04em; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Pood</a>
                        <a :href="route('movies.index')" style="color:#ffc85a; font-size:0.85rem; letter-spacing:0.04em; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Filmid</a>
                    </div>
                    <div v-if="$page.props.auth.user">
                        <Link :href="route('movies.create')"
                            style="background:rgba(255,200,90,0.12); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:7px 16px; border-radius:8px; font-size:0.8rem; letter-spacing:0.05em; text-decoration:none; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(255,200,90,0.22)'"
                            onmouseout="this.style.background='rgba(255,200,90,0.12)'">
                            Lisa uus
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            <!-- Source Switcher -->
            <div class="flex items-center gap-4 mb-6">
                <div style="display:flex; border:1px solid rgba(255,200,90,0.15); border-radius:8px; overflow:hidden;">
                    <button @click="switchSource('mine')"
                        :style="activeSource === 'mine'
                            ? 'background:rgba(255,200,90,0.15); color:#ffc85a; padding:7px 16px; font-size:0.8rem; border:none; cursor:pointer; letter-spacing:0.04em;'
                            : 'background:transparent; color:#6b6358; padding:7px 16px; font-size:0.8rem; border:none; cursor:pointer; letter-spacing:0.04em;'">
                        Minu andmed
                    </button>
                    <button @click="switchSource('friend')"
                        :style="activeSource === 'friend'
                            ? 'background:rgba(255,200,90,0.15); color:#ffc85a; padding:7px 16px; font-size:0.8rem; border:none; border-left:1px solid rgba(255,200,90,0.15); cursor:pointer; letter-spacing:0.04em;'
                            : 'background:transparent; color:#6b6358; padding:7px 16px; font-size:0.8rem; border:none; border-left:1px solid rgba(255,200,90,0.15); cursor:pointer; letter-spacing:0.04em;'">
                        Teine API
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div v-if="activeSource === 'mine'" style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:12px; padding:1.25rem; margin-bottom:2rem;">
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1" style="min-width:200px;">
                        <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:6px;">Otsi</label>
                        <input v-model="search" type="text" placeholder="Pealkiri..."
                            @keyup.enter="applyFilters"
                            style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:8px 12px; color:#e2ddd5; font-size:0.85rem; outline:none;"
                            onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                            onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                    </div>
                    <div style="width:150px;">
                        <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:6px;">Žanr</label>
                        <select v-model="genre" @change="applyFilters"
                            style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:8px 12px; color:#e2ddd5; font-size:0.85rem; outline:none;">
                            <option value="">Kõik</option>
                            <option v-for="g in genres" :key="g" :value="g">{{ g }}</option>
                        </select>
                    </div>
                    <div style="width:150px;">
                        <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:6px;">Sorteeri</label>
                        <select v-model="sort" @change="applyFilters"
                            style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:8px 12px; color:#e2ddd5; font-size:0.85rem; outline:none;">
                            <option value="created_at">Lisamise aeg</option>
                            <option value="title">Pealkiri</option>
                            <option value="release_year">Aasta</option>
                            <option value="rating">Hinnang</option>
                        </select>
                    </div>
                    <div style="width:120px;">
                        <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:6px;">Suund</label>
                        <select v-model="direction" @change="applyFilters"
                            style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:8px 12px; color:#e2ddd5; font-size:0.85rem; outline:none;">
                            <option value="desc">Kahanev</option>
                            <option value="asc">Kasvav</option>
                        </select>
                    </div>
                    <button @click="applyFilters"
                        style="background:rgba(255,200,90,0.15); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:8px 18px; border-radius:8px; font-size:0.8rem; cursor:pointer; letter-spacing:0.05em;">
                        Otsi
                    </button>
                    <button @click="clearFilters"
                        style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); color:#8a8070; padding:8px 18px; border-radius:8px; font-size:0.8rem; cursor:pointer; letter-spacing:0.05em;">
                        Tühjenda
                    </button>
                </div>
            </div>

            

            <!-- Loading -->
            <div v-if="activeSource === 'friend' && friendLoading" style="text-align:center; padding:5rem 0; color:#4a4640;">
                <p style="font-size:1.1rem; letter-spacing:0.1em;">Laadin...</p>
            </div>

            <!-- Error -->
            <div v-else-if="activeSource === 'friend' && friendError" style="text-align:center; padding:5rem 0; color:#8a4040;">
                <p>{{ friendError }}</p>
            </div>

            <!-- Empty -->
            <div v-else-if="displayedMovies.length === 0" style="text-align:center; padding:5rem 0; color:#4a4640;">
                <p style="font-size:1.1rem;">Filme pole veel lisatud.</p>
            </div>

            <!-- Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                <!-- MINU FILMID -->
                <template v-if="activeSource === 'mine'">
                    <div v-for="movie in displayedMovies" :key="movie.id"
                        style="background:#16161f; border:1px solid rgba(255,200,90,0.08); border-radius:12px; overflow:hidden; transition:all 0.25s;"
                        onmouseover="this.style.borderColor='rgba(255,200,90,0.25)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.borderColor='rgba(255,200,90,0.08)'; this.style.transform='';">
                        <Link :href="route('movies.show', movie.id)">
                            <img v-if="getImageSrc(movie)" :src="getImageSrc(movie)" :alt="movie.title"
                                style="width:100%; height:200px; object-fit:cover;" />
                            <div v-else style="width:100%; height:200px; background:#1e1e2a; display:flex; align-items:center; justify-content:center; color:#3a3a4a;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:48px; height:48px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                                </svg>
                            </div>
                        </Link>
                        <div style="padding:1.1rem;">
                            <Link :href="route('movies.show', movie.id)" style="text-decoration:none;">
                                <h3 style="font-size:1rem; font-weight:600; color:#e2ddd5; transition:color 0.2s;"
                                    onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#e2ddd5'">
                                    {{ movie.title }}
                                </h3>
                            </Link>
                            <p style="color:#6b6358; font-size:0.8rem; margin-top:4px;">{{ movie.director }} · {{ movie.release_year }}</p>
                            <div style="display:flex; align-items:center; gap:8px; margin-top:8px; flex-wrap:wrap;">
                                <span v-if="movie.genre"
                                    style="background:rgba(255,200,90,0.1); color:#a88840; font-size:0.7rem; padding:2px 10px; border-radius:20px; letter-spacing:0.06em;">
                                    {{ movie.genre }}
                                </span>
                                <span v-if="movie.rating" style="color:#c8a440; font-size:0.8rem; font-weight:600;">⭐ {{ movie.rating }}/10</span>
                            </div>
                            <p style="color:#5a5650; font-size:0.8rem; margin-top:8px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ movie.description }}</p>
                            <div v-if="$page.props.auth.user?.id === movie.user_id"
                                style="display:flex; gap:12px; margin-top:12px; padding-top:12px; border-top:1px solid rgba(255,200,90,0.08);">
                                <Link :href="route('movies.edit', movie.id)"
                                    style="color:#8a8070; font-size:0.78rem; text-decoration:none; letter-spacing:0.05em;"
                                    onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#8a8070'">
                                    Muuda
                                </Link>
                                <button @click="deleteMovie(movie.id)"
                                    style="color:#8a8070; font-size:0.78rem; background:none; border:none; cursor:pointer; letter-spacing:0.05em; padding:0;"
                                    onmouseover="this.style.color='#c05050'" onmouseout="this.style.color='#8a8070'">
                                    Kustuta
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- SÕBRA RETSEPTID -->
                <template v-else>
                    <div v-for="item in displayedMovies" :key="item.id"
                        @click="openRecipe(item)"
                        style="background:#16161f; border:1px solid rgba(255,200,90,0.08); border-radius:12px; overflow:hidden; transition:all 0.25s; cursor:pointer;"
                        onmouseover="this.style.borderColor='rgba(255,200,90,0.25)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.borderColor='rgba(255,200,90,0.08)'; this.style.transform='';">
                        <img v-if="item.image" :src="item.image" :alt="item.title"
                            style="width:100%; height:200px; object-fit:cover;" />
                        <div v-else style="width:100%; height:200px; background:#1e1e2a; display:flex; align-items:center; justify-content:center; font-size:3rem;">
                            🍽️
                        </div>
                        <div style="padding:1.1rem;">
                            <h3 style="font-size:1rem; font-weight:600; color:#e2ddd5;">{{ item.title }}</h3>
                            <div style="display:flex; align-items:center; gap:8px; margin-top:6px; flex-wrap:wrap;">
                                <span v-if="item.difficulty"
                                    style="background:rgba(255,200,90,0.1); color:#a88840; font-size:0.7rem; padding:2px 10px; border-radius:20px; letter-spacing:0.06em; text-transform:capitalize;">
                                    {{ item.difficulty }}
                                </span>
                                <span v-if="item.cooking_time" style="color:#8a8070; font-size:0.78rem;">⏱ {{ item.cooking_time }} min</span>
                                <span v-if="item.calories" style="color:#8a8070; font-size:0.78rem;">🔥 {{ item.calories }} kcal</span>
                            </div>
                            <p style="color:#5a5650; font-size:0.8rem; margin-top:8px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ item.description }}</p>
                            <div style="margin-top:12px; padding-top:12px; border-top:1px solid rgba(255,200,90,0.08);">
                            </div>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <!-- Retsepti modaal -->
    <div v-if="selectedRecipe"
        style="position:fixed; inset:0; background:rgba(0,0,0,0.75); z-index:100; display:flex; align-items:center; justify-content:center; padding:1rem;"
        @click.self="closeRecipe">
        <div style="background:#16161f; border:1px solid rgba(255,200,90,0.15); border-radius:16px; max-width:600px; width:100%; max-height:90vh; overflow-y:auto;">
            <img v-if="selectedRecipe.image" :src="selectedRecipe.image" :alt="selectedRecipe.title"
                style="width:100%; height:240px; object-fit:cover; border-radius:16px 16px 0 0;" />
            <div style="padding:1.75rem;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
                    <h2 style="font-family:'Georgia',serif; font-size:1.4rem; color:#e2ddd5;">{{ selectedRecipe.title }}</h2>
                    <button @click="closeRecipe"
                        style="color:#6b6358; background:none; border:none; font-size:1.4rem; cursor:pointer; padding:0 0 0 1rem; line-height:1;"
                        onmouseover="this.style.color='#e2ddd5'" onmouseout="this.style.color='#6b6358'">✕</button>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:1.25rem;">
                    <span v-if="selectedRecipe.difficulty"
                        style="background:rgba(255,200,90,0.1); color:#a88840; font-size:0.75rem; padding:3px 12px; border-radius:20px; text-transform:capitalize;">
                        {{ selectedRecipe.difficulty }}
                    </span>
                    <span v-if="selectedRecipe.cooking_time" style="color:#8a8070; font-size:0.82rem;">⏱ {{ selectedRecipe.cooking_time }} min</span>
                    <span v-if="selectedRecipe.calories" style="color:#8a8070; font-size:0.82rem;">🔥 {{ selectedRecipe.calories }} kcal</span>
                </div>
                <p style="color:#a8a098; font-size:0.88rem; line-height:1.7; margin-bottom:1.25rem;">{{ selectedRecipe.description }}</p>
                <div v-if="selectedRecipe.instructions">
                    <h3 style="font-size:0.8rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:0.75rem;">Juhised</h3>
                    <p style="color:#a8a098; font-size:0.88rem; line-height:1.8; white-space:pre-line;">{{ selectedRecipe.instructions }}</p>
                </div>
            </div>
        </div>
    </div>

</template>