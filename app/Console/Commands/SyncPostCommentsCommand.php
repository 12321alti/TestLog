<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncPostCommentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-post-comments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync posts and comments from JSON';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('started sych posts and comments');

        $responcePost = Http::get('https://jsonplaceholder.typicode.com/posts');

        if ($responcePost->successful()) {
            $dataPost = $responcePost->json();
            foreach ($dataPost as $post) {
                $user = User::find($post['userId']);
                if (!$user) {
                    continue; 
                }
                
                Post::updateOrCreate(
                    ['id' => $post['id']],
                    [
                        'user_id' => $user->id,
                        'title' => $post['title'],
                        'content' => $post['body'],
                    ]
                );
            }
            $this->info('sucsess');
        }
        else {
            $this->error('error is sucseseful posts');
        }

        $responceComments = Http::get('https://jsonplaceholder.typicode.com/comments');

        if ($responceComments->successful()) {
            $dataComment = $responceComments->json();
            foreach ($dataComment as $comment) {
                $post = Post::find($comment['postId']);
                if (!$post) {
                    continue; 
                }

                $user = User::firstOrCreate(
                    ['email' => $comment['email']],
                    [
                        'name' => $comment['name'],
                        'company' => '---',
                    ]
                );

                Comment::updateOrCreate(
                    ['id' => $comment['id']],
                    [
                        'post_id' => $post->id,
                        'user_id' => $user->id,
                        'text' => $comment['body'],
                    ]
                );
            }
            $this->info('suscsess to sync post and comments');
        }
        else {
            $this->error('error is sucseseful comments');
        }
    }
}
