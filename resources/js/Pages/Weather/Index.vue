<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ weather: Object, city: String, error: String });

const searchCity = ref(props.city || 'Tallinn');

function search() {
    router.get(route('weather.index'), { city: searchCity.value }, { preserveState: true });
}

function getWeatherIcon(iconCode) {
    return `https://openweathermap.org/img/wn/${iconCode}@4x.png`;
}

function getWindDirection(deg) {
    const dirs = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'];
    return dirs[Math.round(deg / 45) % 8];
}
</script>

<template>
    <Head title="Ilm" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-6">
                        <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                        <a :href="route('weather.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Ilm</a>
                        <a :href="route('map.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Kaart</a>
                        <a :href="route('blog.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Blogi</a>
                        <a :href="route('shop.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Pood</a>
                        <a :href="route('movies.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Filmid</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
          

            <!-- Search -->
            <form @submit.prevent="search" style="display:flex; margin-bottom:2.5rem;">
                <input
                    v-model="searchCity"
                    type="text"
                    
                    style="flex:1; background:#16161f; border:1px solid rgba(255,200,90,0.2); border-right:none; border-radius:10px 0 0 10px; padding:12px 18px; color:#e2ddd5; font-size:0.9rem; outline:none;"
                    onfocus="this.style.borderColor='rgba(255,200,90,0.5)'"
                    onblur="this.style.borderColor='rgba(255,200,90,0.2)'" />
                <button type="submit"
                    style="background:rgba(255,200,90,0.15); border:1px solid rgba(255,200,90,0.3); border-radius:0 10px 10px 0; padding:12px 22px; color:#ffc85a; font-size:0.85rem; letter-spacing:0.06em; cursor:pointer;"
                    onmouseover="this.style.background='rgba(255,200,90,0.25)'"
                    onmouseout="this.style.background='rgba(255,200,90,0.15)'">
                    Otsi
                </button>
            </form>

            <!-- Error -->
            <div v-if="error"
                style="background:rgba(180,60,60,0.1); border:1px solid rgba(180,60,60,0.25); color:#c05050; border-radius:10px; padding:1rem 1.25rem; text-align:center; margin-bottom:1.5rem; font-size:0.85rem;">
                {{ error }}
            </div>

            <!-- Weather display -->
            <div v-if="weather" style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:16px; overflow:hidden;">
                <!-- Main -->
                <div style="padding:2rem; background:linear-gradient(135deg, rgba(90,140,255,0.08), rgba(255,200,90,0.04));">
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
                        <div>
                            <h2 style="font-family:'Georgia',serif; font-size:1.6rem; color:#e2ddd5; font-weight:700;">{{ weather.name }}, {{ weather.sys?.country }}</h2>
                            <p style="font-size:4rem; font-weight:200; color:#ffc85a; line-height:1; margin-top:8px; font-family:'Georgia',serif;">{{ Math.round(weather.main?.temp) }}°C</p>
                            <p style="color:#8a8070; font-size:0.9rem; margin-top:6px; text-transform:capitalize;">{{ weather.weather?.[0]?.description }}</p>
                        </div>
                        <div v-if="weather.weather?.[0]?.icon">
                            <img :src="getWeatherIcon(weather.weather[0].icon)" :alt="weather.weather[0].description"
                                style="width:100px; height:100px; filter:drop-shadow(0 0 20px rgba(255,200,90,0.3));" />
                        </div>
                    </div>
                </div>

                <!-- Details -->
                <div style="padding:1.5rem; border-top:1px solid rgba(255,200,90,0.08);">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:10px; padding:1rem; text-align:center;">
                            <p style="color:#4a4640; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:6px;">Tundub nagu</p>
                            <p style="font-size:1.4rem; font-weight:600; color:#c8b896;">{{ Math.round(weather.main?.feels_like) }}°C</p>
                        </div>
                        <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:10px; padding:1rem; text-align:center;">
                            <p style="color:#4a4640; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:6px;">Niiskus</p>
                            <p style="font-size:1.4rem; font-weight:600; color:#c8b896;">{{ weather.main?.humidity }}%</p>
                        </div>
                        <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:10px; padding:1rem; text-align:center;">
                            <p style="color:#4a4640; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:6px;">Tuul</p>
                            <p style="font-size:1.4rem; font-weight:600; color:#c8b896;">{{ weather.wind?.speed }} m/s</p>
                            <p style="font-size:0.72rem; color:#4a4640; margin-top:2px;">{{ getWindDirection(weather.wind?.deg) }}</p>
                        </div>
                        <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:10px; padding:1rem; text-align:center;">
                            <p style="color:#4a4640; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:6px;">Rõhk</p>
                            <p style="font-size:1.4rem; font-weight:600; color:#c8b896;">{{ weather.main?.pressure }}</p>
                            <p style="font-size:0.72rem; color:#4a4640; margin-top:2px;">hPa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-3">
                        <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:10px; padding:0.9rem; text-align:center;">
                            <p style="color:#4a4640; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:5px;">Min / Max</p>
                            <p style="font-size:1rem; font-weight:600; color:#c8b896;">{{ Math.round(weather.main?.temp_min) }}° / {{ Math.round(weather.main?.temp_max) }}°</p>
                        </div>
                        <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:10px; padding:0.9rem; text-align:center;">
                            <p style="color:#4a4640; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:5px;">Pilvisus</p>
                            <p style="font-size:1rem; font-weight:600; color:#c8b896;">{{ weather.clouds?.all }}%</p>
                        </div>
                        <div style="background:rgba(255,200,90,0.04); border:1px solid rgba(255,200,90,0.08); border-radius:10px; padding:0.9rem; text-align:center;">
                            <p style="color:#4a4640; font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:5px;">Nähtavus</p>
                            <p style="font-size:1rem; font-weight:600; color:#c8b896;">{{ (weather.visibility / 1000).toFixed(1) }} km</p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!weather && !error" style="text-align:center; color:#4a4640; padding:4rem 0;">
                <p style="font-size:2rem; margin-bottom:1rem;">🌍</p>
                <p>Otsi linna, et näha ilmaandmeid.</p>
            </div>
        </div>
    </div>
</template>