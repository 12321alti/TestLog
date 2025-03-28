<?php

namespace App\Http\Controllers\API\V1;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $page = $request->input('page', 1);
        $query = $request->input('query');

        $posts = Post::with(['user', 'comments.user'])->when($query, function ($q) use($query){
            $q->where('title', 'like', '%' . $query . '%')->orWhere('content', 'like', '%' . $query . '%')
            ->orWhereHas('user', function($q) use($query) {
                $q->where('name', 'like', '%' . $query . '%');
            });
        })->paginate($perPage, ['*'], 'page', $page);

        $formatedPost = $posts->map(function ($post) {
            return [
                'title' => $post->title,
                'content' => $post->content,
                'author' => [
                    'name' => $post->user->name,
                    'email' => $post->user->email,
                ],
                'comments' => $post->comments->map(function ($comment) {
                        return [
                            'text' => $comment->text,
                            'author' => [
                                'name' => $comment->user->name,
                                'email' => $comment->user->email,
                            ],
                        ];
                    })
                ];
            });
        return response()->json($formatedPost);
    }
}
