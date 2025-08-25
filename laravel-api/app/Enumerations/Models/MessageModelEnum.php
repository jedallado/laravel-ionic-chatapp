<?php

namespace App\Enumerations\Models;

use App\Enumerations\BaseEnumTrait;

enum MessageModelEnum: string
{
    use BaseEnumTrait;

    const TABLE_NAME = 'messages';

    case ChatroomId = 'chatroom_id';
    case SenderId = 'sender_id';
    case Message = 'message';

    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';

    public static function fillable(): array {
        return [
            self::getChatRoomId(),
            self::getSenderId(),
            self::getMessage()
        ];
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function getChatRoomId(): string {
        return self::ChatroomId->value;
    }

    public static function getSenderId(): string {
        return self::SenderId->value;
    }

    public static function getMessage(): string {
        return self::Message->value;
    }

    public static function createdAt(): string {
        return self::CREATED_AT->value;
    }

    public static function updatedAt(): string {
        return self::UPDATED_AT->value;
    }
}
