<?php
namespace Chord\Http\User\Controllers;

use Chord\Domain\User\Events\OpenChat;
use Chord\Domain\User\Events\UserReactedOnHouse;
use Chord\Domain\User\Services\ReactionService;
use Chord\Http\Controller;
use Chord\Http\User\Requests\ReactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReactionController extends Controller
{
    /**
     * @var ReactionService
     */
    private $service;

    /**
     * ReactionController constructor.
     * @param ReactionService $service
     */
    public function __construct(ReactionService $service)
    {
        $this->service = $service;
    }

    /**
     * @param ReactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ReactionRequest $request)
    {
        $result = $this->service->create($request->house, $request->action, Auth::id());
        $chatEvent = $this->service->isChatAvailable($result);

        event(new UserReactedOnHouse($result));
        if($chatEvent){
            event(new OpenChat(Auth::user(), $result->userB));
        }

        return response()->json(['like' => $result->like]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        $removedUserId = $this->service->remove($request->house, Auth::id());

        return response()->json(['status' => 'ok', 'user' => $removedUserId]);
    }
}