<?php

namespace App\Listeners;


use App\Events\EmailEvent;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailListener
{

    protected $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  EmailEvent  $event
     * @return void
     */
    public function handle(EmailEvent $event)
    {
        return $this->mailer->send([], [], function ($m) use ($event) {
            $m->from(config('mail.from.address'), config('db.site_name'));
            $m->to($event->to)->subject($event->subject);
            $m->setBody($event->body, 'text/html');
        });
    }
}
