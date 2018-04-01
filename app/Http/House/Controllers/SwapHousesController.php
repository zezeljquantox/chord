<?php

namespace Chord\Http\House\Controllers;

use Chord\Domain\House\Events\UserSwapHouse;
use Chord\Domain\House\Services\SwapHouseService;
use Chord\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwapHousesController extends Controller
{
    /**
     * @var SwapHouseService
     */
    private $service;

    /**
     * SwapHousesController constructor.
     * @param SwapHouseService $service
     */
    public function __construct(SwapHouseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        list($houseA, $houseB) = $this->service->swapHouses(Auth::user(), $request->user);
        event(new UserSwapHouse($houseB, $houseB->address, $houseA->user));
        return response()->json(['id' => $houseB->user_id, 'tenant' => $houseA->user_id, 'address' => $houseA->address->full_address]);
    }
}