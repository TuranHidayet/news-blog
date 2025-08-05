<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CommentImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function handle()
    {


        $response = Http::get('https://jsonplaceholder.typicode.com/comments');

        if ($response->successful()) {
            $comments = $response->json();

            foreach ($comments as $data) {
                Comment::updateOrCreate(
                    ['id' => $data['id']],
                    [
                        'postId' => $data['postId'],
                        'name'   => $data['name'],
                        'email'  => $data['email'],
                        'body'   => $data['body'],
                    ]
                );
            }
        }
         Log::info('CommentImportJob başladı!');

    }
}
