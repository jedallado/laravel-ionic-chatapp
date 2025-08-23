<?php

namespace App\Enumerations\Models;

use App\Enumerations\BaseEnumTrait;

enum UserDeviceTokenEnum: string
{
    use BaseEnumTrait;

    const TABLE_NAME = 'user_device_tokens';

    case UserId = 'user_id';
    case DeviceName = 'device_name';
    case Token = 'token';

    public static function fillable(): array {
        return [
            self::userId(),
            self::token(),
            self::getAddress(),
        ];
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function userId(): string {
        return self::UserId->value;
    }

    public static function deviceName(): string {
        return self::DeviceName->value;
    }

    public static function token(): string {
        return self::Token->value;
    }
}
