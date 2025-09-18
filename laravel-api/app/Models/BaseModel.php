<?php

namespace App\Models;


use App\Traits\Searchable;
use Carbon\Carbon;
use MongoDB\Laravel\Eloquent\Model;

class BaseModel extends Model
{
    use Searchable;
    protected $connection = 'mongodb';

    public static function formatDateToReadable($value) {
        if (!$value) {
            return '';
        }

        $date = Carbon::parse($value);
        $now = Carbon::now();

        if ($date->isToday()) {
            $diffInSeconds = $now->diffInSeconds($date, true);
            if ($diffInSeconds < 15) {
                return 'a few seconds ago';
            }

            return $date->diffForHumans();
        } elseif ($date->greaterThanOrEqualTo($now->copy()->startOfWeek()) && $date->lessThanOrEqualTo($now)) {
            return $date->format('D h:ia'); // Mon 11:23AM
        } elseif ($date->isCurrentYear()) {
            return $date->format('F j'); // April 15
        } else {
            return $date->format('M d, Y'); // Jan 01, 2024
        }
    }
}
