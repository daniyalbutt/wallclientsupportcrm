<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    
    public $invoice_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $invoice_id)
    {
        $this->message  = $message;
        $this->invoice_id  = $invoice_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['realtime-notify'];
    }

    public function broadcastAs() {

        return 'notify-event';
       
        }
       
}

    

