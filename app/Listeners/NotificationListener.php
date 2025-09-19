<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\NotificationJob;

class NotificationListener
{
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
    public function handle(NotificationEvent $event): void
    {
        //dispatch
        NotificationJob::dispatch($event->action, $event->module, $event->isAdmin_module, $event->sender, $event->reciever, $event->item_affected, $event->updated_at,
                                $event->url, $event->notify_client, $event->notify_trainer, $event->notify_admin);
    }
}
