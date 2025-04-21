<?php

namespace App\Models;


class EmailVerificationCode extends BaseModel
{
    protected $fillable = ['verification_code', 'user_id'];
}
