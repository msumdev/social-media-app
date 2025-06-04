<?php

namespace App\Jobs;

use App\Models\User\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PostDeletedJob implements ShouldQueue
{
    use Queueable;

    private string $postId;

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
