<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'open_time',
        'close_time',
        'lunch_start',
        'lunch_end',
        'every_other_week',
        'eow_start_date',
    ];
}
