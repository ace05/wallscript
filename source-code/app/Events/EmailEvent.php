<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EmailEvent extends Event
{
    use SerializesModels;
    
    public $to;

    public $subject;

    public $body;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $body)
    {
        $this->to = $to;
        $this->body = $body;
        $this->subject = $subject;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
