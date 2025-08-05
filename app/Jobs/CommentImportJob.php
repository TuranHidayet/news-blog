<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CommentImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url; 

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function handle()
    {
        Log::info("CommentImportJob başladı. API URL: {$this->url}");

        $response = Http::get($this->url);

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
    }
}
