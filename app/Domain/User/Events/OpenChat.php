<?php

namespace Chord\Domain\User\Events;

use Chord\Domain\User\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OpenChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userA;
    public $userB;

    /**
     * Create a new event instance.
     *
     * @param User $userA
     * @param User $userB
     */
    public function __construct(User $userA, User $userB)
    {
        $this->userA = $userA;
        $this->userB = $userB;
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
        return 'OpenChat';
    }
}