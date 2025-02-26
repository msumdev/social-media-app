<?php

namespace App\Listeners;

use App\Events\Reverb\ConnectionClosed;
use App\Models\Session;
use App\Models\User\User;
use App\Services\ReverbConnectionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;

class ReverbConnectionClosedListener implements ShouldQueue
{
    /**
     * @var string $queue
     */
    public string $queue = 'reverb';

    /**
     * Create the event listener.
     */
    public function __construct(protected ReverbConnectionService $reverbConnectionService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ConnectionClosed $event): void
    {
        $sessionString = $event->cookies['wise_session'];

        $sessionString = substr($sessionString, 0, -3);

        $sessionString = Crypt::decryptString($sessionString);

        $split = explode('|', $sessionString);

        $sessionId = $split[1];

        $session = Session::where('id', $sessionId)->first();

        if (isset($session->user_id)) {
            $userId = $session->user_id;

            $this->reverbConnectionService->remove($userId);
        }
    }
}
