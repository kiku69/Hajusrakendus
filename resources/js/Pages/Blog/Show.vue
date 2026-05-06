<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({ post: Object });

const commentForm = useForm({ body: '' });

function submitComment() {
    commentForm.post(route('comments.store', props.post.id), {
        onSuccess: () => commentForm.reset(),
    });
}

function deleteComment(id) {
    if (confirm('Kas oled kindel?')) {
        router.delete(route('comments.destroy', id));
    }
}

function deletePost() {
    if (confirm('Kas oled kindel, et soovid selle postituse kustutada?')) {
        router.delete(route('blog.destroy', props.post.id));
    }
}
</script>

<template>
    <Head :title="post.title" />

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
                </div>
            </div>
        </nav>

        <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <Link :href="route('blog.index')"
                style="color:#6b6358; font-size:0.8rem; text-decoration:none; letter-spacing:0.08em; display:inline-block; margin-bottom:2rem;"
                onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#6b6358'">
                Tagasi
            </Link>

            <!-- Post -->
            <article style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:14px; padding:2.25rem;">
                <h1 style="font-family:'Georgia',serif; font-size:1.8rem; font-weight:700; color:#e2ddd5; line-height:1.3;">{{ post.title }}</h1>
                <div style="display:flex; align-items:center; gap:12px; margin-top:12px; flex-wrap:wrap;">
                    <span style="font-size:0.78rem; color:#6b6358;">{{ post.user?.name }}</span>
                    <span style="color:#2a2a30;">·</span>
                    <span style="font-size:0.78rem; color:#6b6358;">{{ new Date(post.created_at).toLocaleDateString('et-EE') }}</span>
                    <span v-if="post.updated_at !== post.created_at" style="font-size:0.75rem; color:#4a4640; font-style:italic;">
                        Muudetud: {{ new Date(post.updated_at).toLocaleDateString('et-EE') }}
                    </span>
                </div>

                <div v-if="$page.props.auth.user?.id === post.user_id"
                    style="display:flex; gap:12px; margin-top:14px;">
                    <Link :href="route('blog.edit', post.id)"
                        style="font-size:0.78rem; color:#8a8070; text-decoration:none; letter-spacing:0.05em;"
                        onmouseover="this.style.color='#ffc85a'" onmouseout="this.style.color='#8a8070'">Muuda</Link>
                    <button @click="deletePost"
                        style="font-size:0.78rem; color:#8a8070; background:none; border:none; cursor:pointer; letter-spacing:0.05em; padding:0;"
                        onmouseover="this.style.color='#c05050'" onmouseout="this.style.color='#8a8070'">Kustuta</button>
                </div>

                <div style="margin-top:1.75rem; color:#a8a098; line-height:1.8; font-size:0.9rem; white-space:pre-wrap;">{{ post.description }}</div>
            </article>

            <!-- Comments -->
            <div style="margin-top:2.5rem;">
                <h2 style="font-size:1rem; font-weight:600; color:#c8b896; letter-spacing:0.06em; margin-bottom:1.25rem;">
                    Kommentaarid ({{ post.comments?.length || 0 }})
                </h2>

                <!-- Comment form -->
                <div v-if="$page.props.auth.user"
                    style="background:#16161f; border:1px solid rgba(255,200,90,0.1); border-radius:12px; padding:1.25rem; margin-bottom:1.25rem;">
                    <form @submit.prevent="submitComment">
                        <textarea v-model="commentForm.body" rows="3" placeholder="Lisa kommentaar..." required
                            style="width:100%; background:#0d0d12; border:1px solid rgba(255,200,90,0.15); border-radius:8px; padding:10px 14px; color:#e2ddd5; font-size:0.85rem; outline:none; resize:vertical; box-sizing:border-box;"
                            onfocus="this.style.borderColor='rgba(255,200,90,0.4)'"
                            onblur="this.style.borderColor='rgba(255,200,90,0.15)'"></textarea>
                        <div v-if="commentForm.errors.body" style="color:#b04040; font-size:0.78rem; margin-top:4px;">{{ commentForm.errors.body }}</div>
                        <button type="submit" :disabled="commentForm.processing"
                            style="margin-top:10px; background:rgba(255,200,90,0.12); border:1px solid rgba(255,200,90,0.25); color:#ffc85a; padding:8px 18px; border-radius:8px; font-size:0.8rem; letter-spacing:0.05em; cursor:pointer;">
                            Lisa kommentaar
                        </button>
                    </form>
                </div>
                <div v-else style="background:#16161f; border:1px solid rgba(255,200,90,0.08); border-radius:12px; padding:1rem; text-align:center; margin-bottom:1.25rem;">
                    <Link :href="route('login')" style="color:#ffc85a; text-decoration:none; font-size:0.85rem;">Logi sisse</Link>
                    <span style="color:#4a4640; font-size:0.85rem;">, et kommenteerida.</span>
                </div>

                <!-- Comments list -->
                <div style="display:flex; flex-direction:column; gap:0.75rem;">
                    <div v-for="comment in post.comments" :key="comment.id"
                        style="background:#16161f; border:1px solid rgba(255,200,90,0.06); border-radius:10px; padding:1rem;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <div>
                                <p style="font-size:0.85rem; font-weight:600; color:#c8b896;">{{ comment.user?.name }}</p>
                                <p style="font-size:0.72rem; color:#4a4640; margin-top:2px;">{{ new Date(comment.created_at).toLocaleDateString('et-EE') }}</p>
                            </div>
                            <button
                                v-if="$page.props.auth.user && ($page.props.auth.user.id === comment.user_id || $page.props.auth.user.id === post.user_id)"
                                @click="deleteComment(comment.id)"
                                style="font-size:0.75rem; color:#4a4640; background:none; border:none; cursor:pointer;"
                                onmouseover="this.style.color='#c05050'" onmouseout="this.style.color='#4a4640'">
                                Kustuta
                            </button>
                        </div>
                        <p style="color:#a8a098; font-size:0.85rem; margin-top:8px; line-height:1.6;">{{ comment.body }}</p>
                    </div>
                </div>

                <div v-if="!post.comments?.length" style="text-align:center; color:#3a3a40; font-size:0.85rem; padding:2rem 0;">
                    Kommentaare pole.
                </div>
            </div>
        </div>
    </div>
</template>