<?php

namespace App\Enumerations\Models;

use App\Enumerations\BaseEnumTrait;

enum ChatRoomModelEnum: string
{
    use BaseEnumTrait;

    const TABLE_NAME = 'chat_rooms';

    case RoomName = 'room_name';
    case Members = 'members';
    case LastMessage = 'last_message';

    case DefaultChatRoomName = 'NONAME';

    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';

    public static function fillable(): array {
        return [
            self::getRoomName(),
            self::getMembers(),
            self::getLastMessage(),
        ];
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function getRoomName(): string {
        return self::RoomName->value;
    }

    public static function getMembers(): string {
        return self::Members->value;
    }

    public static function getLastMessage(): string {
        return self::LastMessage->value;
    }

    public static function getDefaultChatRoomName(): string {
        return self::DefaultChatRoomName->value;
    }

    public static function getCreatedAt(): string {
        return self::CREATED_AT->value;
    }

    public static function getUpdatedAt(): string {
        return self::UPDATED_AT->value;
    }
}
