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
        'current_month_expense',
        'current_total_collection',
        'current_month_total_guest_amount',
        'current_month_total_cash_on_hand',
        'current_month_total_amount',
        'current_total_remaining',
        'current_month_total_eat_day',
        'current_month_total_cut_day',
        'current_month_total_day',
        'current_month_profit'
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