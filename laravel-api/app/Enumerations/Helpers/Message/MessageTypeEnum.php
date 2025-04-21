<?php

namespace App\Enumerations\Helpers\Message;

use App\Enumerations\BaseEnumTrait;

enum MessageTypeEnum: string
{
    use BaseEnumTrait;

    case Sent = 'sent';
    case Received = 'received';

    public static function getSent() {
        return self::Sent->value;
    }

    public static function getReceived() {
        return self::Received->value;
    }
}
