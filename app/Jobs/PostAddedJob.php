<?php

namespace App\Jobs;

use App\Models\Posts\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PostAddedJob implements ShouldQueue
{
    use Queueable;

    /**
     * @var Post $post
     */
    private Post $post;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->post->friends instanceof \Illuminate\Database\Eloquent\Collection) {
            foreach ($this->post->friends as $friend) {
                event(new \App\Events\Posts\PostAdded($this->post, $friend));
            }
        }
    }
}
