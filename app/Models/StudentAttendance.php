<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'attendance_date',
        'is_present',
        'is_feast_day',
        'student_charge',
        'simple_guest_count',
        'simple_guest_charge',
        'feast_guest_count',
        'feast_guest_charge',
        'remark',
    ];

    protected $casts = [
        'is_present' => 'boolean',
        'is_feast_day' => 'boolean',
        'attendance_date' => 'date',
        'student_charge' => 'float',
        'simple_guest_count' => 'integer',
        'simple_guest_charge' => 'float',
        'feast_guest_count' => 'integer',
        'feast_guest_charge' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}