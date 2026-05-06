<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';

const props = defineProps({
    markers: Array,
});

const showForm = ref(false);
const editingMarker = ref(null);
const mapContainer = ref(null);
let map = null;
let leafletMarkers = [];
let tempMarker = null;

const form = useForm({
    name: '',
    latitude: 59.437,
    longitude: 24.7536,
    description: '',
});

onMounted(async () => {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);

    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = () => {
        initMap();
    };
    document.head.appendChild(script);
});

watch(() => props.markers, (newMarkers) => {
    if (!map) return;
    leafletMarkers.forEach(({ leafletMarker }) => {
        map.removeLayer(leafletMarker);
    });
    leafletMarkers = [];
    newMarkers.forEach(marker => {
        addLeafletMarker(marker);
    });
}, { deep: true });

function initMap() {
    map = L.map(mapContainer.value).setView([59.437, 24.7536], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    props.markers.forEach(marker => {
        addLeafletMarker(marker);
    });

    map.on('click', function (e) {
        form.latitude = parseFloat(e.latlng.lat.toFixed(7));
        form.longitude = parseFloat(e.latlng.lng.toFixed(7));
        editingMarker.value = null;
        form.name = '';
        form.description = '';
        showForm.value = true;

        if (tempMarker) {
            map.removeLayer(tempMarker);
        }
        tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
            icon: L.icon({
                iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
            }),
            opacity: 0.6,
        }).addTo(map);
    });
}

function addLeafletMarker(marker) {
    const lm = L.marker([marker.latitude, marker.longitude]).addTo(map);
    lm.bindPopup(`
        <strong>${escapeHtml(marker.name)}</strong><br/>
        ${marker.description ? escapeHtml(marker.description) + '<br/>' : ''}
        <small>Lat: ${marker.latitude}, Lng: ${marker.longitude}</small>
    `);
    leafletMarkers.push({ id: marker.id, leafletMarker: lm });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function editMarker(marker) {
    editingMarker.value = marker;
    form.name = marker.name;
    form.latitude = marker.latitude;
    form.longitude = marker.longitude;
    form.description = marker.description || '';
    showForm.value = true;
}

function submitForm() {
    if (editingMarker.value) {
        form.put(route('markers.update', editingMarker.value.id), {
            onSuccess: () => {
                showForm.value = false;
                editingMarker.value = null;
                form.reset();
                if (tempMarker) {
                    map.removeLayer(tempMarker);
                    tempMarker = null;
                }
            },
        });
    } else {
        form.post(route('markers.store'), {
            onSuccess: () => {
                showForm.value = false;
                form.reset();
                if (tempMarker) {
                    map.removeLayer(tempMarker);
                    tempMarker = null;
                }
            },
        });
    }
}

function deleteMarker(marker) {
    if (confirm('Kas oled kindel, et soovid selle markeri kustutada?')) {
        router.delete(route('markers.destroy', marker.id));
    }
}

function cancelForm() {
    showForm.value = false;
    editingMarker.value = null;
    form.reset();
    if (tempMarker) {
        map.removeLayer(tempMarker);
        tempMarker = null;
    }
}
</script>

<template>
    <Head title="Kaart" />

    <div class="min-h-screen bg-gray-100">
        <!-- Nav -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-8">
                        <a :href="route('dashboard')" class="text-gray-800 font-bold text-lg">Hajusrakendused</a>
                        <a :href="route('weather.index')" class="text-gray-500 hover:text-gray-700">Ilm</a>
                        <a :href="route('map.index')" class="text-gray-900 font-medium">Kaart</a>
                        <a :href="route('blog.index')" class="text-gray-500 hover:text-gray-700">Blogi</a>
                        <a :href="route('shop.index')" class="text-gray-500 hover:text-gray-700">Pood</a>
                        <a :href="route('movies.index')" class="text-gray-500 hover:text-gray-700">Filmid</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Kaardirakendus</h1>
                <p class="text-gray-500 text-sm">Klõpsa kaardil, et lisada uus marker</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Map -->
                <div class="lg:col-span-2">
                    <div ref="mapContainer" class="w-full h-[600px] rounded-xl shadow-lg border border-gray-200 z-0"></div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <!-- Add/Edit Form -->
                    <div v-if="showForm" class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            {{ editingMarker ? 'Muuda markerit' : 'Lisa uus marker' }}
                        </h3>
                        <form @submit.prevent="submitForm" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nimi</label>
                                <input v-model="form.name" type="text" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Laiuskraad</label>
                                    <input v-model="form.latitude" type="number" step="any" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Pikkuskraad</label>
                                    <input v-model="form.longitude" type="number" step="any" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kirjeldus</label>
                                <textarea v-model="form.description" rows="3"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" :disabled="form.processing"
                                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg transition">
                                    {{ editingMarker ? 'Salvesta' : 'Lisa' }}
                                </button>
                                <button type="button" @click="cancelForm"
                                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 rounded-lg transition">
                                    Tühista
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Markers List -->
                    <div class="bg-white rounded-xl shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Markerid ({{ markers.length }})</h3>
                        <div v-if="markers.length === 0" class="text-gray-400 text-center py-4">
                            Markereid pole lisatud.
                        </div>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <div v-for="marker in markers" :key="marker.id"
                                class="border border-gray-100 rounded-lg p-3 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ marker.name }}</p>
                                        <p v-if="marker.description" class="text-gray-500 text-sm mt-1">{{ marker.description }}</p>
                                        <p class="text-gray-400 text-xs mt-1">{{ marker.latitude }}, {{ marker.longitude }}</p>
                                    </div>
                                    <div class="flex space-x-1 ml-2">
                                        <button @click="editMarker(marker)"
                                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                            Muuda
                                        </button>
                                        <button @click="deleteMarker(marker)"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Kustuta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>