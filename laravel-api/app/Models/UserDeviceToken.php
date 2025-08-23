<?php

namespace App\Models;

use App\Enumerations\Models\UserDeviceTokenEnum;
use Illuminate\Database\Eloquent\Model;

class UserDeviceToken extends BaseModel
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getFillable()
    {
        return UserDeviceTokenEnum::fillable();
    }

    public function scopeOfUserId($query, $userId) {
        if (!$userId) {
            return [];
        }

        return $query->where(UserDeviceTokenEnum::userId(), '=', $userId);
    }
}
