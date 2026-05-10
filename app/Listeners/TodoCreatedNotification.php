<?php

namespace App\Listeners;

use App\Events\TodoCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TodoCreatedNotification implements ShouldQueue
{

    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TodoCreated $event): void
    {
        $todo = $event->todo;

        Log::info("Processing Todo #{$todo->id} in the queue...");
    }
}
