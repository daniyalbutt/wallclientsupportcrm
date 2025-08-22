<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTaskEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    
    public $task_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $task_id)
    {
        $this->message  = $message;
        $this->task_id  = $task_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['realtime-notify-task'];
    }

    public function broadcastAs() {

        return 'notify-event-task';
       
    }
    
    public function broadcastConnection()
    {
        return 'pusher_second'; // Use the second Pusher app connection
    }
       
}

    

