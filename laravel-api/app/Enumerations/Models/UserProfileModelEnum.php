<?php

namespace App\Enumerations\Models;

use App\Enumerations\BaseEnumTrait;

enum UserProfileModelEnum: string
{
    use BaseEnumTrait;

    const TABLE_NAME = 'user_profiles';

    case UserId = 'user_id';
    case FirstName = 'firstname';
    case LastName = 'lastname';
    case FullName = 'full_name';
    case Address = 'address';
    case Gender = 'gender';
    case Photo = 'photo';

    public static function fillable(): array {
        return [
            self::getFirstName(),
            self::getLastName(),
            self::getAddress(),
            self::getGender(),
            self::getUserId(),
            self::getPhoto()
        ];
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function getUserId(): string {
        return self::UserId->value;
    }

    public static function getFirstName(): string {
        return self::FirstName->value;
    }

    public static function getLastName(): string {
        return self::LastName->value;
    }

    public static function getFullName(): string {
        return self::FullName->value;
    }

    public static function getAddress(): string {
        return self::Address->value;
    }

    public static function getGender(): string {
        return self::Gender->value;
    }

    public static function getPhoto(): string {
        return self::Photo->value;
    }
}
