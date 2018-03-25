<?php

namespace Chord\Http\User\Controllers;

use Chord\Domain\User\Services\ChatService;
use Chord\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * @var ChatService
     */
    private $service;

    /**
     * ChatController constructor.
     * @param ChatService $service
     */
    public function __construct(ChatService $service)
    {
        $this->service = $service;
    }

    public function getChat(Request $request)
    {
        $chat = $this->service->getChat(Auth::id(), $request->user);
        return response()->json(['chat' => $chat]);
    }
}