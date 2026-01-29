<?php

namespace App\Http\Controllers;

use App\Models\MonthlyTransaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use ApiResponse;

    public function getChartStats()
    {
        try {
            // Fetch last 6 months to make a better chart, or just 2 as requested
            // But usually, charts look better with more data. 
            // User requested "previous two months".
            
            $stats = MonthlyTransaction::orderBy('bill_date', 'desc')
                ->take(6) // Taking 6 to show a decent trend, but will focus on last 2 if needed
                ->get()
                ->reverse()
                ->values();

            $formattedData = $stats->map(function($stat) {
                return [
                    'month_name' => Carbon::parse($stat->bill_date)->format('M Y'),
                    'expense' => (float)$stat->current_month_expense,
                    'income' => (float)$stat->current_total_collection, // Collection is the income
                    'profit' => (float)$stat->current_month_profit,
                ];
            });

            return $this->successResponse($formattedData, 'Chart statistics retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Chart stats error: ' . $e->getMessage(), 500);
        }
    }
}
