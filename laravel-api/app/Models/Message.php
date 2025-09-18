<?php

namespace App\Models;

use App\Enumerations\Helpers\Message\MessageTypeEnum;
use App\Enumerations\Models\MessageModelEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

    public function getMessage() {
        return $this->{MessageModelEnum::getMessage()};
    }

    protected function type(): Attribute
    {
        $user = Auth::user();

        $type = $this->getSenderId() === $user->id ? MessageTypeEnum::sent() : MessageTypeEnum::received();

        return Attribute::make(
            get: fn($value, array $attributes) => $type,
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            return self::formatDateToReadable($value);
        });
    }
}
