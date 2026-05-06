<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('blog.show', $post);
    }

    public function destroy(Comment $comment)
    {
        // Allow comment owner or post owner to delete
        $post = $comment->post;

        if ($comment->user_id !== auth()->id() && $post->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->route('blog.show', $post);
    }
}
