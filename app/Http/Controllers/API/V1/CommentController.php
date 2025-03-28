<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentController extends Controller
{
    public function index(Request $request) {
        $comments = Comment::with(['user', 'post'])->get();

        $formatedComments = $comments->map(function ($comment) {
            return [
                'postId' => $comment->post_id, 
                'name' => $comment->user->name,
                'email' => $comment->user->email,
                'text' => $comment->text,
            ];
        });

        return response()->json($formatedComments);
    }

    public function store(Request $request) {
        $fields = $request->validate([
            'post_id' => 'required|integer|exists:posts,id', 
            'name' => 'required|string|max:255', 
            'email' => 'required|email|max:255', 
            'text' => 'required|string|max:255',
        ]);

        $post = Post::find($fields['post_id']);

        if (!$post) {
            return response()->json(['error' => 'No post'], 404);
        }

        $user = User::firstOrCreate(
            ['email' => $fields['email']],
            ['name' => $fields['name']]
        );

        Comment::create([
            'text' => $fields['text'],
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        return response()->json(['message' => 'Comment saved'], 201);
    }

}

