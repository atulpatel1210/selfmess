<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'total_day',
        'total_eat_day',
        'cut_day',
        'amount',
        'date',
        'simple_guest',
        'simple_guest_amount',
        'feast_guest',
        'feast_guest_amount',
        'due_amount',
        'penalty_amount',
        'total_amount',
        'paid_amount',
        'remain_amount',
        'remark',
        'rate',
        'rate_with_guest',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}