<?php

namespace App\Enumerations;

trait BaseEnumTrait
{
    public function values(): array {
        return array_column(self::cases(), 'value');
    }

    public static function getId(): string {
        return 'id';
    }
}
