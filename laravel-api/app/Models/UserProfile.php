<?php

namespace App\Models;

use App\Enumerations\Models\UserProfileModelEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;

class UserProfile extends BaseModel
{
    protected $appends = ['full_name'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getFillable()
    {
        return UserProfileModelEnum::fillable();
    }

    protected function fullName(): Attribute
    {
        $fullName = $this->{UserProfileModelEnum::getFirstName()} . " " . $this->{UserProfileModelEnum::getLastName()};
        return Attribute::make(
            get: fn($value, array $attributes) => $fullName
        );
    }
}
