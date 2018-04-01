<?php

namespace Chord\Domain\House\Events;

use Chord\Domain\Address\Models\Address;
use Chord\Domain\House\Models\House;
use Chord\Domain\User\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSwapHouse implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var House
     */
    public $house;

    /**
     * @var Address
     */
    public $address;

    /**
     * @var User
     */
    public $user;
    /**
     * UserSwapHouse constructor.
     * @param House $house
     * @param Address $address
     */
    public function __construct(House $house, Address $address, User $user)
    {
        $this->house = $house;
        $this->address = $address;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('house-swap');
    }

    public function broadcastAs()
    {
        return 'UserSwappedHouse';
    }
}