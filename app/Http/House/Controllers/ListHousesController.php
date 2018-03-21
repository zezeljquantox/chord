<?php

namespace Chord\Http\House\Controllers;

use Chord\Domain\House\Services\ListHousesService;
use Chord\Http\Controller;

/**
 * Class ListHousesController
 * @package Chord\Http\House\Controllers
 */
class ListHousesController extends Controller
{
    /**
     * @var ListHousesService
     */
    private $service;

    /**
     * ListHousesController constructor.
     * @param ListHousesService $service
     */
    public function __construct(ListHousesService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $houses = $this->service->getHouses(auth()->user()->id);
        $userIds = $houses->pluck('user_id')->toArray();

        $reactions = $this->service->getReactions(auth()->user()->id, $userIds);

        $mapedReactions = $this->service->mapHousesReactions(auth()->user()->id, $reactions);
        return view("house.index")->with(['houses' => $houses, 'mapedReactions' => $mapedReactions]);
    }
}