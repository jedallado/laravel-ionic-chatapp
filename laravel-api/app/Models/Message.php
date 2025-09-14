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
            $date = Carbon::parse($value);
            $now = Carbon::now();

            if ($date->isToday()) {
                $diffInSeconds = $now->diffInSeconds($date, true);
                if ($diffInSeconds < 15) {
                    return 'a few seconds ago';
                }

                return $date->diffForHumans();
            } elseif ($date->greaterThanOrEqualTo($now->copy()->startOfWeek()) && $date->lessThanOrEqualTo($now)) {
                return $date->format('D h:ia'); // Mon 11:23AM
            } elseif ($date->isCurrentYear()) {
                return $date->format('F j'); // April 15
            } else {
                return $date->format('M d, Y'); // Jan 01, 2024
            }
        });
    }
}
