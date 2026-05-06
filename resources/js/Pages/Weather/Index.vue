<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    weather: Object,
    city: String,
    error: String,
});

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

    <div class="min-h-screen bg-gradient-to-br from-blue-400 via-blue-500 to-indigo-600">
        <!-- Nav -->
        <nav class="bg-white/10 backdrop-blur-md border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-8">
                        <a :href="route('dashboard')" class="text-white font-bold text-lg">Hajusrakendused</a>
                        <a :href="route('weather.index')" class="text-white/90 hover:text-white font-medium">Ilm</a>
                        <a :href="route('map.index')" class="text-white/70 hover:text-white">Kaart</a>
                        <a :href="route('blog.index')" class="text-white/70 hover:text-white">Blogi</a>
                        <a :href="route('shop.index')" class="text-white/70 hover:text-white">Pood</a>
                        <a :href="route('movies.index')" class="text-white/70 hover:text-white">Filmid</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-white text-center mb-8">Ilmateade</h1>

            <!-- Search -->
            <div class="flex justify-center mb-10">
                <form @submit.prevent="search" class="flex w-full max-w-lg">
                    <input
                        v-model="searchCity"
                        type="text"
                        placeholder="Otsi linna (nt Tallinn, London, Tokyo)..."
                        class="flex-1 rounded-l-xl px-6 py-3 text-gray-800 bg-white/90 backdrop-blur focus:outline-none focus:ring-2 focus:ring-yellow-400 text-lg"
                    />
                    <button
                        type="submit"
                        class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold px-6 py-3 rounded-r-xl transition"
                    >
                        Otsi
                    </button>
                </form>
            </div>

            <!-- Error -->
            <div v-if="error" class="bg-red-500/80 backdrop-blur text-white rounded-xl p-6 text-center mb-6">
                {{ error }}
            </div>

            <!-- Weather Display -->
            <div v-if="weather" class="bg-white/15 backdrop-blur-lg rounded-2xl p-8 text-white shadow-2xl">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <!-- Left: City & Temp -->
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-bold">{{ weather.name }}, {{ weather.sys?.country }}</h2>
                        <p class="text-6xl font-light mt-2">{{ Math.round(weather.main?.temp) }}°C</p>
                        <p class="text-xl capitalize mt-1 text-white/80">{{ weather.weather?.[0]?.description }}</p>
                    </div>

                    <!-- Right: Icon -->
                    <div v-if="weather.weather?.[0]?.icon" class="mt-4 md:mt-0">
                        <img
                            :src="getWeatherIcon(weather.weather[0].icon)"
                            :alt="weather.weather[0].description"
                            class="w-32 h-32 drop-shadow-lg"
                        />
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <div class="text-white/60 text-sm mb-1">Tundub nagu</div>
                        <div class="text-2xl font-semibold">{{ Math.round(weather.main?.feels_like) }}°C</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <div class="text-white/60 text-sm mb-1">Niiskus</div>
                        <div class="text-2xl font-semibold">{{ weather.main?.humidity }}%</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <div class="text-white/60 text-sm mb-1">Tuul</div>
                        <div class="text-2xl font-semibold">{{ weather.wind?.speed }} m/s</div>
                        <div class="text-xs text-white/50">{{ getWindDirection(weather.wind?.deg) }}</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <div class="text-white/60 text-sm mb-1">Rõhk</div>
                        <div class="text-2xl font-semibold">{{ weather.main?.pressure }} hPa</div>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <div class="text-white/60 text-sm mb-1">Min / Max</div>
                        <div class="text-lg font-semibold">
                            {{ Math.round(weather.main?.temp_min) }}° / {{ Math.round(weather.main?.temp_max) }}°
                        </div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <div class="text-white/60 text-sm mb-1">Pilvisus</div>
                        <div class="text-lg font-semibold">{{ weather.clouds?.all }}%</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-4 text-center">
                        <div class="text-white/60 text-sm mb-1">Nähtavus</div>
                        <div class="text-lg font-semibold">{{ (weather.visibility / 1000).toFixed(1) }} km</div>
                    </div>
                </div>
            </div>

            <!-- No data -->
            <div v-if="!weather && !error" class="text-center text-white/70 text-lg">
                Otsi linna, et näha ilmaandmeid.
            </div>
        </div>
    </div>
</template>
