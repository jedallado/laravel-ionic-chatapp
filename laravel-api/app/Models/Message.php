<?php

namespace App\Models;

use App\Enumerations\Helpers\Message\MessageTypeEnum;
use App\Enumerations\Models\MessageModelEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;

class Message extends BaseModel
{
    protected $appends = ['type'];

    public function sender() {
        return $this->belongsTo(User::class);
    }

    public function getFillable()
    {
        return MessageModelEnum::fillable();
    }

    public function getChatroomId() {
        return $this->{MessageModelEnum::getChatRoomId()};
    }

    public function getSenderId() {
        return $this->{MessageModelEnum::getSenderId()};
    }

    protected function type(): Attribute
    {
        $user = Auth::user();

        $type = $this->getSenderId() === $user->id ? MessageTypeEnum::getSent() : MessageTypeEnum::getReceived();

        return Attribute::make(
            get: fn($value, array $attributes) => $type,
        );
    }
}
