<script setup>
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';

const props = defineProps({ markers: Array });

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
    script.onload = () => { initMap(); };
    document.head.appendChild(script);
});

watch(() => props.markers, (newMarkers) => {
    if (!map) return;
    leafletMarkers.forEach(({ leafletMarker }) => map.removeLayer(leafletMarker));
    leafletMarkers = [];
    newMarkers.forEach(marker => addLeafletMarker(marker));
}, { deep: true });

function initMap() {
    map = L.map(mapContainer.value).setView([59.437, 24.7536], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 19,
    }).addTo(map);
    props.markers.forEach(marker => addLeafletMarker(marker));
    map.on('click', function (e) {
        form.latitude = parseFloat(e.latlng.lat.toFixed(7));
        form.longitude = parseFloat(e.latlng.lng.toFixed(7));
        editingMarker.value = null;
        form.name = '';
        form.description = '';
        showForm.value = true;
        if (tempMarker) map.removeLayer(tempMarker);
        tempMarker = L.marker([e.latlng.lat, e.latlng.lng], { opacity: 0.6 }).addTo(map);
    });
}

function addLeafletMarker(marker) {
    const lm = L.marker([marker.latitude, marker.longitude]).addTo(map);
    lm.bindPopup(`<strong>${escapeHtml(marker.name)}</strong><br/>
        ${marker.description ? escapeHtml(marker.description) + '<br/>' : ''}
        <small style="color:#888;">Lat: ${marker.latitude}, Lng: ${marker.longitude}</small>`);
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
            onSuccess: () => { showForm.value = false; editingMarker.value = null; form.reset(); if (tempMarker) { map.removeLayer(tempMarker); tempMarker = null; } },
        });
    } else {
        form.post(route('markers.store'), {
            onSuccess: () => { showForm.value = false; form.reset(); if (tempMarker) { map.removeLayer(tempMarker); tempMarker = null; } },
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
    if (tempMarker) { map.removeLayer(tempMarker); tempMarker = null; }
}
</script>

<template>
    <Head title="Kaart" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-6">
                        <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                        <a :href="route('weather.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Ilm</a>
                        <a :href="route('map.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Kaart</a>
                        <a :href="route('blog.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Blogi</a>
                        <a :href="route('shop.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Pood</a>
                        <a :href="route('movies.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Filmid</a>
                    </div>
                    <p style="font-size:0.75rem; color:#4a4640; letter-spacing:0.06em;">Klõpsa kaardil markeri lisamiseks</p>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 style="font-family:'Georgia',serif; font-size:1.75rem; color:#e2ddd5; margin-bottom:1.25rem;">Kaardirakendus</h1>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Map -->
                <div class="lg:col-span-2">
                    <div ref="mapContainer"
                        style="width:100%; height:600px; border-radius:12px; border:1px solid rgba(255,200,90,0.1); overflow:hidden; z-index:0;"></div>
                </div>

                <!-- Sidebar -->
                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <!-- Add/Edit form -->
                    <div v-if="showForm" style="background:#16161f; border:1px solid rgba(255,200,90,0.15); border-radius:12px; padding:1.5rem;">
                        <h3 style="font-size:0.95rem; font-weight:600; color:#c8b896; margin-bottom:1.25rem;">
                            {{ editingMarker ? 'Muuda markerit' : 'Lisa uus marker' }}
                        </h3>
                        <form @submit.prevent="submitForm" style="display:flex; flex-direction:column; gap:0.9rem;">
                            <div>
                                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:5px;">Nimi</label>
                                <input v-model="form.name" type="text" required
                                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.85rem; outline:none; box-sizing:border-box;"
                                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                                <div v-if="form.errors.name" style="color:#b04040; font-size:0.75rem; margin-top:3px;">{{ form.errors.name }}</div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:5px;">Lat</label>
                                    <input v-model="form.latitude" type="number" step="any" required
                                        style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.82rem; outline:none; box-sizing:border-box;"
                                        onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                        onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                                </div>
                                <div>
                                    <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:5px;">Lng</label>
                                    <input v-model="form.longitude" type="number" step="any" required
                                        style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.82rem; outline:none; box-sizing:border-box;"
                                        onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                        onblur="this.style.borderColor='rgba(255,200,90,0.15)'" />
                                </div>
                            </div>
                            <div>
                                <label style="display:block; font-size:0.72rem; color:#6b6358; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:5px;">Kirjeldus</label>
                                <textarea v-model="form.description" rows="3"
                                    style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:9px 12px; color:#e2ddd5; font-size:0.85rem; outline:none; resize:vertical; box-sizing:border-box;"
                                    onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                                    onblur="this.style.borderColor='rgba(255,200,90,0.15)'"></textarea>
                            </div>
                            <div style="display:flex; gap:8px;">
                                <button type="submit" :disabled="form.processing"
                                    style="flex:1; background:rgba(255,200,90,0.15); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:9px; border-radius:8px; font-size:0.82rem; cursor:pointer; letter-spacing:0.04em;"
                                    onmouseover="this.style.background='rgba(255,200,90,0.25)'"
                                    onmouseout="this.style.background='rgba(255,200,90,0.15)'">
                                    {{ editingMarker ? 'Salvesta' : 'Lisa' }}
                                </button>
                                <button type="button" @click="cancelForm"
                                    style="flex:1; background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); color:#8a8070; padding:9px; border-radius:8px; font-size:0.82rem; cursor:pointer;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.08)'"
                                    onmouseout="this.style.background='rgba(255,255,255,0.04)'">
                                    Tühista
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Markers list -->
                    <div style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:12px; padding:1.5rem; flex:1;">
                        <h3 style="font-size:0.95rem; font-weight:600; color:#c8b896; margin-bottom:1rem;">
                            Markerid <span style="color:#4a4640; font-weight:400;">({{ markers.length }})</span>
                        </h3>

                        <div v-if="markers.length === 0" style="text-align:center; color:#3a3a40; padding:2rem 0; font-size:0.85rem;">
                            Markereid pole lisatud.<br/>
                            <span style="font-size:0.78rem; color:#2a2a30;">Klõpsa kaardil alustamiseks.</span>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:0.6rem; max-height:430px; overflow-y:auto;">
                            <div v-for="marker in markers" :key="marker.id"
                                style="background:rgba(255,200,90,0.03); border:1px solid rgba(255,200,90,0.07); border-radius:8px; padding:0.9rem; transition:all 0.2s;"
                                onmouseover="this.style.borderColor='rgba(255,200,90,0.18)'"
                                onmouseout="this.style.borderColor='rgba(255,200,90,0.07)'">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px;">
                                    <div style="flex:1; min-width:0;">
                                        <p style="font-size:0.88rem; font-weight:600; color:#e2ddd5; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ marker.name }}</p>
                                        <p v-if="marker.description" style="font-size:0.78rem; color:#5a5650; margin-top:3px; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ marker.description }}</p>
                                        <p style="font-size:0.7rem; color:#3a3a40; margin-top:4px; font-family:monospace;">{{ marker.latitude }}, {{ marker.longitude }}</p>
                                    </div>
                                    <div style="display:flex; flex-direction:column; gap:4px; flex-shrink:0;">
                                        <button @click="editMarker(marker)"
                                            style="font-size:0.72rem; color:#6b6358; background:none; border:none; cursor:pointer; padding:0; text-align:right; letter-spacing:0.04em;"
                                            onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#6b6358'">Muuda</button>
                                        <button @click="deleteMarker(marker)"
                                            style="font-size:0.72rem; color:#6b6358; background:none; border:none; cursor:pointer; padding:0; text-align:right; letter-spacing:0.04em;"
                                            onmouseover="this.style.color='#c05050'" onmouseout="this.style.color='#6b6358'">Kustuta</button>
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