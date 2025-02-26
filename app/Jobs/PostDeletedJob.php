<?php

namespace App\Jobs;

use App\Models\Posts\Post;
use App\Models\User\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PostDeletedJob implements ShouldQueue
{
    use Queueable;

    /**
     * @var string $postId
     */
    private string $postId;

    /**
     * @var User $user
     */
    private User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(string $postId, User $user)
    {
        $this->postId = $postId;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->user->friends instanceof \Illuminate\Database\Eloquent\Collection) {
            foreach ($this->user->friends as $friend) {
                event(new \App\Events\Posts\PostDeleted($this->postId, $friend));
            }
        }
    }
}
