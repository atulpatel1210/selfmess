<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bill_date',
        'year',
        'month',
        'total_guest_amount',
        'total_cash_on_hand',
        'total_collection',
        'total_amount',
        'end_month_cash_on_hand',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bill_date' => 'date',
        'total_guest_amount' => 'decimal:2',
        'total_cash_on_hand' => 'decimal:2',
        'total_collection' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'end_month_cash_on_hand' => 'decimal:2',
        'year' => 'integer',
        'month' => 'integer',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'monthly_transactions';
}