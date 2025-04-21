<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public string $recipientId;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message, string $recipientId)
    {
        $this->message = $message;
        $this->recipientId = $recipientId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        Log::info('Connecting to channel: ' . 'chat.' . $this->message->getChatroomId());
        return [
            new Channel('chat.' . $this->message->getChatroomId()),
        ];
    }

    public function broadcastAs(): string {
        return 'message.sent';
    }

    public function broadcastWith(): array {
        return $this->message->toArray();
    }
}
