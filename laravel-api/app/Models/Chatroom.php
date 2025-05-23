<?php

namespace App\Models;

use App\Enumerations\Models\ChatRoomModelEnum;
use App\Enumerations\Models\UserProfileModelEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Chatroom extends BaseModel
{

    // RELATED MODELS
    public function messages() {
        return $this->hasMany(Message::class);
    }
    // END OF RELATED MODELS

    public function getFillable()
    {
        return ChatRoomModelEnum::fillable();
    }

    // CUSTOM FUNCTIONS
    public function setLastMessage(Message $message) {
        $this->{ChatRoomModelEnum::getLastMessage()} = $message->toArray();
    }

    public function setDefaultRoomName() {
        $this->{ChatRoomModelEnum::getRoomName()} = ChatRoomModelEnum::getDefaultChatRoomName();
    }
    // END OF CUSTOM FUNCTIONS

    // ACCESSORS AND MUTATORS
    protected function roomName(): Attribute
    {
        $user = Auth::user();
        $name = $this->{ChatRoomModelEnum::getRoomName()} ?? ChatRoomModelEnum::getDefaultChatRoomName();
        $members = $this->{ChatRoomModelEnum::getMembers()};

        if ($members && $name === ChatRoomModelEnum::getDefaultChatRoomName()) {
            $members = Arr::where($this->{ChatRoomModelEnum::getMembers()}, function ($value) use ($user) {
                return $value !== $user->id;
            });

            $memberProfiles = UserProfile::whereIn(UserProfileModelEnum::getUserId(), $members)->get();

            if (count($memberProfiles) === 1) {
                $name = $this->directRoomName($memberProfiles->first());
            } else {
                $name = $this->groupChatRoomName($memberProfiles);
            }
        }

        return Attribute::make(
            get: fn($value, array $attributes) => $name,
        );
    }

    public function getId() {
        return $this->{ChatRoomModelEnum::getId()};
    }

    public function getMembers() {
        return $this->{ChatRoomModelEnum::getMembers()};
    }
    // END OF ACCESSORS AND MUTATORS

    // PRIVATE FUNCTIONS
    private function directRoomName($member): string {
        return $member->{UserProfileModelEnum::getFullName()};
    }

    private function groupChatRoomName($members): string {
        $groupChatName = '';

        foreach ($members as $member) {
            if ($groupChatName) {
                $groupChatName = $groupChatName . ", ";
                continue;
            }

            $groupChatName = $member->{UserProfileModelEnum::getFirstName()};
        }

        return $groupChatName;
    }
    // END OF PRIVATE FUNCTIONS
}
