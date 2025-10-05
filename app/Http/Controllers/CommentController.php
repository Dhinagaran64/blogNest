<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $post = Post::findOrFail($postId);

        $comment = new Comment();
        $comment->comment = $request->input('comment');
        $comment->user_id = auth()->id();
        $comment->post_id = $post->id;

        $comment->save();

        return redirect()->route('post.show', $postId);
    }

    public function update(Request $request, $commentId)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment = Comment::findOrFail($commentId);
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()->back();
    }

    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully.');
    }

}
