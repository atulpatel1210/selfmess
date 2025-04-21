<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'total_day',
        'eat_day',
        'cut_day',
        'student_charge',
        'simple_guest',
        'simple_guest_charge',
        'feast_guest',
        'feast_guest_charge',
        'due_amount',
        'penalty_amount',
        'total_bill',
        'paid_bill',
        'remain_amount',
        'remark',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}