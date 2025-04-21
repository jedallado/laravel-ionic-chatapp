<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\ChatRoomModelEnum;
use App\Http\Controllers\Controller;
use App\Models\Chatroom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $chatrooms = Chatroom::whereIn(ChatRoomModelEnum::getMembers(), [$user->getId()])
            ->orderByDesc(ChatRoomModelEnum::getUpdatedAt())
            ->get();
        return response()->json($chatrooms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $chatroom = Chatroom::with('messages')->find($id);

        return response()->json($chatroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
