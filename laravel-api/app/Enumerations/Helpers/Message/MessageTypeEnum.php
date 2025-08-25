<?php

namespace App\Enumerations\Helpers\Message;

use App\Enumerations\BaseEnumTrait;

enum MessageTypeEnum: string
{
    use BaseEnumTrait;

    case Sent = 'sent';
    case Received = 'received';

    public static function sent() {
        return self::Sent->value;
    }

    public static function received() {
        return self::Received->value;
    }
}
