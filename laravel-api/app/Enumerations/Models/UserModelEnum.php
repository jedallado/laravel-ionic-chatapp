<?php

namespace App\Enumerations\Models;

use App\Enumerations\BaseEnumTrait;

enum UserModelEnum: string
{
    use BaseEnumTrait;

    const TABLE_NAME = 'users';

    case Username = 'username';
    case Email = 'email';
    case Password = 'password';
    case RememberToken = 'remember_token';
    case EmailVerifiedAt = 'email_verified_at';

    public static function fillable(): array {
        return [
            self::getUsername(),
            self::getEmail(),
            self::getPassword()
        ];
    }

    public static function hidden(): array {
        return [
            self::getPassword(),
            self::getRememberToken()
        ];
    }

    public static function getTableName(): string {
        return self::TABLE_NAME;
    }

    public static function getUsername(): string {
        return self::Username->value;
    }

    public static function getEmail(): string {
        return self::Email->value;
    }

    public static function getPassword(): string {
        return self::Password->value;
    }

    public static function getRememberToken(): string {
        return self::RememberToken->value;
    }

    public static function getEmailVerifiedAt(): string {
        return self::EmailVerifiedAt->value;
    }
}
