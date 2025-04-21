<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RateMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'rate',
        'simple_guest_rate',
        'feast_guest_rate',
    ];
}