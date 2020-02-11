<?php

namespace App\Listeners;

use App\Entities\Notification;
use App\Events\NotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  Notification  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
        if($event->data['action'] == 'add'){
            return Notification::create($event->data);
        }
        else{
            return Notification::where('update_id', '=', $event->data['update_id'])
                                ->where('from_user_id', '=', $event->data['from_user_id'])
                                ->where('to_user_id', '=', $event->data['to_user_id'])
                                ->where('type', '=', $event->data['type'])
                                ->delete();
        }
    }
}
