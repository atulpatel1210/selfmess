<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'amount',
        'date',
        'remark',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}