<?php

namespace App\Enumerations\Models;

use App\Enumerations\BaseEnumTrait;

enum ChatRoomModelEnum: string
{
    use BaseEnumTrait;

    const TABLE_NAME = 'chatrooms';

    case RoomName = 'room_name';
    case Members = 'members';
    case LastMessage = 'last_message';

    case DefaultChatRoomName = 'NONAME';

    case CREATED_AT = 'created_at';
    case UPDATED_AT = 'updated_at';

    public static function fillable(): array {
        return [
            self::roomName(),
            self::members(),
            self::lastMessage(),
        ];
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function roomName(): string {
        return self::RoomName->value;
    }

    public static function members(): string {
        return self::Members->value;
    }

    public static function lastMessage(): string {
        return self::LastMessage->value;
    }

    public static function defaultChatRoomName(): string {
        return self::DefaultChatRoomName->value;
    }

    public static function createdAt(): string {
        return self::CREATED_AT->value;
    }

    public static function updatedAt(): string {
        return self::UPDATED_AT->value;
    }
}
