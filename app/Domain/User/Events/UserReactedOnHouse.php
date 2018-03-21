<?php

namespace Chord\Domain\User\Events;

use Chord\Domain\User\Models\Reaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserReactedOnHouse implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reaction;

    /**
     * Create a new event instance.
     *
     * @param Reaction $reaction
     */
    public function __construct(Reaction $reaction)
    {
        $this->reaction = $reaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //channel
        return new Channel('user-reacted');
    }

    public function broadcastAs()
    {
        //event
        return 'UserReactedOnHouse';
    }

}
