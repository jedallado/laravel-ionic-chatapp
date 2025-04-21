<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\ChatRoomModelEnum;
use App\Enumerations\Models\MessageModelEnum;
use App\Events\MessageReceived;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chatroom;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $chatroomId = $request->get('chatroom_id');
        $chatroom = Chatroom::find($chatroomId);

        // if there's no chatroom yet, create a chat room first
        if (!$chatroom) {
            $chatroom = new Chatroom($request->only(ChatRoomModelEnum::fillable())); // Chatroom::create($request->only(ChatRoomModelEnum::fillable()));
            $chatroom->setDefaultRoomName();
            $chatroom->save();
        }

        $message = new Message($request->only(MessageModelEnum::fillable()));
        $message = $chatroom->messages()->save($message);

        // set the last message in the chat room
        $chatroom->setLastMessage($message);
        $chatroom->save();

        Log::info($chatroom);

        $members = $chatroom->getMembers();
        // send this to the very first recipient for now, work on group chat later
        broadcast(new MessageSent($message, $members[0]));
        broadcast(new MessageSent($message, $members[1]));

        // we broadcast these events so that all members of the chatroom will be notified when they are in the chatroom list page
        broadcast(new MessageReceived($message, $members[0]));
        broadcast(new MessageReceived($message, $members[1]));

        return response()->json($message, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
