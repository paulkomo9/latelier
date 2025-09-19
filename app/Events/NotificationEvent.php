<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $module;
    public $isAdmin_module;
    public $sender;
    public $reciever;
    public $item_affected; 
    public $updated_at; 
    public $url; 
    public $notify_client;
    public $notify_trainer;
    public $notify_admin;

    /**
     * Create a new event instance.
     */
    public function __construct($action, $module, $isAdmin_module, $sender, $reciever, $item_affected, $updated_at, $url, $notify_client, $notify_trainer, $notify_admin)
    {
        $this->action = $action;
        $this->module = $module;
        $this->isAdmin_module = $isAdmin_module;
        $this->sender = $sender;
        $this->reciever = $reciever;
        $this->item_affected = $item_affected;
        $this->updated_at = $updated_at;
        $this->url = $url;
        $this->notify_client = $notify_client;
        $this->notify_trainer = $notify_trainer;
        $this->notify_admin = $notify_admin;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
