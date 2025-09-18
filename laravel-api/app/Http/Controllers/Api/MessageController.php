<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\ChatRoomModelEnum;
use App\Enumerations\Models\MessageModelEnum;
use App\Enumerations\Models\UserDeviceTokenEnum;
use App\Enumerations\Models\UserModelEnum;
use App\Events\MessageReceived;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chatroom;
use App\Models\Message;
use App\Models\User;
use App\Models\UserDeviceToken;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    private PushNotificationService $pushNotificationService;

    public function __construct(PushNotificationService $pushNotificationService)
    {
        $this->pushNotificationService =  $pushNotificationService;
    }

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
        $currentLoggedUserId = Auth::id();

        // if there's no chatroom yet, create a chat room first
        if (!$chatroom) {
            $members = [
                $currentLoggedUserId,
                $request->get(MessageModelEnum::getSenderId())
            ];
            $chatroom = new Chatroom([
                'members' => $members
            ]); // Chatroom::create($request->only(ChatRoomModelEnum::fillable()));
            $chatroom->setDefaultRoomName();
            $chatroom->save();
        }

        $message = new Message($request->only(MessageModelEnum::fillable()));
        $message->{MessageModelEnum::getSenderId()} = $currentLoggedUserId;
        $message = $chatroom->messages()->save($message);

        // set the last message in the chat room
        $chatroom->setLastMessage($message);
        $chatroom->save();

        // $message->created_at_readable = Message::formatDateToReadable($message->created_at);
        $members = $chatroom->getMembers();
        // send this to the very first recipient for now, work on group chat later
        broadcast(new MessageSent($message, $members[0]));
        broadcast(new MessageSent($message, $members[1]));

        // we broadcast these events so that all members of the chatroom will be notified when they are in the chatroom list page
        broadcast(new MessageReceived($message, $members[0]));
        broadcast(new MessageReceived($message, $members[1]));

        $currentUser = Auth::user();
        $senderName = $currentUser->profile->full_name;
        $recipients = array_diff($members, [$currentUser->getId()]);

        // send push notifications
        foreach ($recipients as $recipientId) {
            $recipient = User::find($recipientId);
            if ($recipient) {
                $deviceTokens = UserDeviceToken::ofUserId($recipient->{UserModelEnum::getId()})->get();

                foreach ($deviceTokens as $deviceToken) {
                    $token = $deviceToken->{UserDeviceTokenEnum::token()};

                    if ($token) {
                        $this->pushNotificationService->sendPushNotificationUsingServiceAccount(
                            $token,
                            $senderName,
                            $message->getMessage()
                        );
                    }
                }
            }
        }

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
