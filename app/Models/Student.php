<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hostel_name',
        'room_no',
        'email',
        'residential_address',
        'currently_pursuing',
        'currently_studying_year',
        'date',
        'year',
        'mobile',
        'alternative_mobile',
        'advisor_guide',
        'blood_group',
        'deposit',
        'user_id',
        'registration_no',
        'college_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::deleting(function ($student) {
            $student->user()->delete();
        });
    }
}