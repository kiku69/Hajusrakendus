<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({ posts: Array });
</script>

<template>
    <Head title="Blogi" />

    <div class="min-h-screen" style="background:#0d0d12; color:#e2ddd5;">
        <nav style="background:rgba(13,13,18,0.97); border-bottom:1px solid rgba(255,200,90,0.12); position:sticky; top:0; z-index:50; backdrop-filter:blur(16px);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center space-x-6">
                        <a :href="route('dashboard')" style="font-family:'Georgia',serif; font-size:0.95rem; font-weight:700; letter-spacing:0.08em; color:#ffc85a; text-decoration:none;">HAJUS</a>
                        <a :href="route('weather.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Ilm</a>
                        <a :href="route('map.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Kaart</a>
                        <a :href="route('blog.index')" style="color:#ffc85a; font-size:0.85rem; text-decoration:none; border-bottom:1px solid rgba(255,200,90,0.4); padding-bottom:1px;">Blogi</a>
                        <a :href="route('shop.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Pood</a>
                        <a :href="route('movies.index')" style="color:#6b6358; font-size:0.85rem; text-decoration:none;" onmouseover="this.style.color='#c8b896'" onmouseout="this.style.color='#6b6358'">Filmid</a>
                    </div>
                    <div v-if="$page.props.auth.user">
                        <Link :href="route('blog.create')"
                            style="background:rgba(255,200,90,0.12); border:1px solid rgba(255,200,90,0.3); color:#ffc85a; padding:7px 16px; border-radius:8px; font-size:0.8rem; letter-spacing:0.05em; text-decoration:none;"
                            onmouseover="this.style.background='rgba(255,200,90,0.22)'"
                            onmouseout="this.style.background='rgba(255,200,90,0.12)'">
                            Uus postitus
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            

            <div v-if="posts.length === 0" style="text-align:center; padding:5rem 0; color:#4a4640;">
                <p style="font-size:1.1rem;">Postitusi pole veel.</p>
                <Link v-if="$page.props.auth.user" :href="route('blog.create')"
                    style="color:#ffc85a; text-decoration:none; font-size:0.85rem; display:inline-block; margin-top:12px;"
                    onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
                    Lisa esimene postitus →
                </Link>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem;">
                <Link v-for="post in posts" :key="post.id" :href="route('blog.show', post.id)"
                    style="display:block; background:#16161f; border:1px solid rgba(255,200,90,0.08); border-radius:12px; padding:1.5rem; text-decoration:none; transition:all 0.25s;"
                    onmouseover="this.style.borderColor='rgba(255,200,90,0.25)'; this.style.transform='translateX(4px)';"
                    onmouseout="this.style.borderColor='rgba(255,200,90,0.08)'; this.style.transform='';">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
                        <div style="flex:1;">
                            <h2 style="font-size:1.05rem; font-weight:600; color:#e2ddd5; margin-bottom:8px;">{{ post.title }}</h2>
                            <p style="color:#5a5650; font-size:0.85rem; line-height:1.6; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ post.description }}</p>
                            <div style="display:flex; align-items:center; gap:16px; margin-top:12px;">
                                <span style="font-size:0.75rem; color:#4a4640;">{{ post.user?.name }}</span>
                                <span style="color:#2a2a30;">·</span>
                                <span style="font-size:0.75rem; color:#4a4640;">{{ new Date(post.created_at).toLocaleDateString('et-EE') }}</span>
                                <span style="color:#2a2a30;">·</span>
                                <span style="font-size:0.75rem; color:#4a4640;">{{ post.comments?.length || 0 }} kommentaari</span>
                            </div>
                        </div>
                        <span style="color:#3a3a40; font-size:1.2rem; flex-shrink:0; margin-top:2px;">→</span>
                    </div>
                </Link>
            </div>
        </div>
    </div>
</template>