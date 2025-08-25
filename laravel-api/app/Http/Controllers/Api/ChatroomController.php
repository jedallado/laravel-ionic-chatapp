<?php

namespace App\Http\Controllers\Api;

use App\Enumerations\Models\ChatRoomModelEnum;
use App\Enumerations\Models\MessageModelEnum;
use App\Http\Controllers\Controller;
use App\Models\Chatroom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MongoDB\Collection;

class ChatroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $pipeline = [
            [
                '$match' => [
                    'members' => $user->getId()
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'user_profiles',
                    'localField' => 'members',
                    'foreignField' => 'user_id',
                    'as' => 'user_profiles'
                ]
            ],
            [
                '$addFields' => [
                    'user_profiles' => [
                        '$map' => [
                            'input' => [
                                '$filter' => [
                                    'input' => '$user_profiles',
                                    'as' => 'user_profile',
                                    'cond' => [
                                        '$ne' => ['$$user_profile.user_id', $user->getId()]
                                    ]
                                ],
                            ],
                            'as' => 'user_profile',
                            'in' => [
                                'id' => ['$toString' => '$$user_profile._id'],
                                'firstname' => '$$user_profile.firstname',
                                'lastname' => '$$user_profile.lastname',
                                'photo' => '$$user_profile.photo',
                            ]
                        ]
                    ]
                ]
            ],
            [
                '$project' => [
                    'id' => ['$toString' => '$_id'],
                    '_id' => 0,
                    'room_name' => 1,
                    'last_message' => 1,
                    'members' => 1,
                    'user_profiles._id' => ['$toString' => '$_id'],
                    'user_profiles.firstname' => 1,
                    'user_profiles.lastname' => 1,
                    'user_profiles.photo' => 1
                ]
            ],
            [
                '$sort' => [
                    'last_message.created_at' => -1 // -1 = descending, 1 = ascending
                ]
            ]
        ];

        $chatrooms = DB::table(ChatRoomModelEnum::getTableName())->raw(function (Collection $collection) use ($pipeline) {
            return $collection->aggregate($pipeline);
        })->toArray();

        foreach ($chatrooms as $chatroom) {
            if ($chatroom[ChatRoomModelEnum::roomName()] === ChatRoomModelEnum::defaultChatRoomName()) {
                $chatroom[ChatRoomModelEnum::roomName()] = $chatroom->user_profiles[0]->firstname . " " . $chatroom->user_profiles[0]->lastname;
            }
        }

        return response()->json($chatrooms);

        /*$user = Auth::user();
        $chatrooms = Chatroom::whereIn(ChatRoomModelEnum::members(), [$user->getId()])
            ->orderByDesc(ChatRoomModelEnum::updatedAt())
            ->get();
        return response()->json($chatrooms);*/
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
        $chatroom = Chatroom::with(['messages' => function ($query) {
            $query->select(MessageModelEnum::getId(),
                MessageModelEnum::getChatRoomId(),
                MessageModelEnum::getMessage(),
                MessageModelEnum::getSenderId(),
                MessageModelEnum::type(),
                MessageModelEnum::createdAt());
        }])->find($id);

        /*$groupedMessages = collect($chatroom->messages)
            ->groupBy(function ($message) {
                return $message->created_at;
            })->map(function ($messages, $date) {
                return [
                    'date' => $date,
                    'messages' => $messages->values()
                ];
            })->values();

        $chatroomFields = array_merge(['id', 'groupedMessages'], ChatRoomModelEnum::fillable());
        $chatroom['groupedMessages'] = $groupedMessages;*/

        // return response()->json($chatroom->only($chatroomFields));
        return response()->json($chatroom);
        /*return response()->json([
            'chatroom' => $chatroom->only($chatroomFields),
            'messages' => $groupedMessages
        ]);*/
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
