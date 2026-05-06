<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'comments')->latest()->get();

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function create()
    {
        return Inertia::render('Blog/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $request->user()->posts()->create($validated);

        return redirect()->route('blog.index');
    }

    public function show(Post $post)
    {
        $post->load('user', 'comments.user');

        return Inertia::render('Blog/Show', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post)
    {
        $this->authorizePost($post);

        return Inertia::render('Blog/Edit', [
            'post' => $post,
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $this->authorizePost($post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $post->update($validated);

        return redirect()->route('blog.show', $post);
    }

    public function destroy(Post $post)
    {
        $this->authorizePost($post);

        $post->delete();

        return redirect()->route('blog.index');
    }

    private function authorizePost(Post $post): void
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
