<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayMeal extends Model
{
    protected $fillable = [
        'day',
        'breakfast',
        'lunch',
        'dinner'
    ];
}

