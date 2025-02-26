<?php

namespace App\Jobs\Auth;

use App\Mail\Auth\ResetPasswordEmail;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    /**
     * Create a new job instance.
     */
    public function __construct($userId)
    {
        $this->user = User::find($userId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->password_reset_token = Str::random(32);
        $this->user->password_reset_at = now();
        $this->user->save();

        Mail::to($this->user->email)->send(new ResetPasswordEmail($this->user));
    }
}
