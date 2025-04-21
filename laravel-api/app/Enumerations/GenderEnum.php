<?php

namespace App\Enumerations;

enum GenderEnum: string
{
    use BaseEnumTrait;

    case Male = 'Male';
    case Female = 'Female';

    public static function getMale() {
        return self::Male->value;
    }

    public static function getFemale() {
        return self::Female->value;
    }
}
