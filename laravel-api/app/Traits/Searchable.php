<?php

namespace App\Traits;

use MongoDB\BSON\Regex;

trait Searchable
{
    public function scopeStartsWith($query, $fieldName, $searchQuery) {
        return $query->where($fieldName, 'regexp', new Regex("^$searchQuery", 'i'));
    }

    public function scopeContains($query, $fieldName, $searchQuery) {
        return $query->where($fieldName, 'regexp', new Regex($searchQuery, 'i'));
    }
}
