<?php

namespace App\Models;


use App\Traits\Searchable;
use MongoDB\Laravel\Eloquent\Model;

class BaseModel extends Model
{
    use Searchable;
    protected $connection = 'mongodb';
}
