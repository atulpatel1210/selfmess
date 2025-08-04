<?php

namespace App\Http\Controllers;

use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use App\Models\Expense;
use App\Models\MonthlyTransaction;
use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentDetailController extends Controller
{
    use ApiResponse;

    public function index_old(Request $request)
    {
        try {
            $studentId = $request->query('student_id');
            $month = $request->query('month');
            $year = $request->query('year');
            $studentName = $request->query('student_name');

            $query = StudentDetail::query()->with('student');

            if ($studentId) {
                $query->where('student_id', $studentId);
            }

            if ($month) {
                $query->whereMonth('date', $month);
            }

            if ($year) {
                $query->whereYear('date', $year);
            }

            if ($studentName) {
                $query->whereHas('student', function ($q) use ($studentName) {
                    $q->where('name', 'like', '%' . $studentName . '%');
                });
            }

            $studentDetails = $query->get();

            return $this->successResponse($studentDetails, 'Student Details retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Index error: ' . $e->getMessage(), 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $studentId = $request->query('student_id');
            $month = $request->query('month');
            $year = $request->query('year');
            $studentName = $request->query('student_name');

            $query = StudentDetail::query()
                ->join('students', 'student_details.student_id', '=', 'students.id')
                ->select('student_details.*', 'students.name as student_name'); // Select fields and alias student name

            if ($studentId) {
                $query->where('student_details.student_id', $studentId);
            }

            if ($month) {
                $query->whereMonth('student_details.date', $month);
            }

            if ($year) {
                $query->whereYear('student_details.date', $year);
            }

            if ($studentName) {
                $query->where('students.name', 'like', '%' . $studentName . '%');
            }

            $studentDetails = $query->get();

            return $this->successResponse($studentDetails, 'Student Details retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Index error: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'total_day' => 'required|integer|min:0',
            'total_eat_day' => 'required|integer|min:0',
            'cut_day' => 'required|integer|min:0',
            // 'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'simple_guest' => 'integer|min:0',
            'simple_guest_amount' => 'numeric|min:0',
            'feast_guest' => 'integer|min:0',
            'feast_guest_amount' => 'numeric|min:0',
            // 'due_amount' => 'numeric|min:0',
            // 'penalty_amount' => 'numeric|min:0',
            // 'total_amount' => 'numeric|min:0',
            // 'paid_amount' => 'numeric|min:0',
            // 'remain_amount' => 'numeric|min:0',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }
        
        $date = Carbon::parse($request->date);
        $month = $date->format('m');
        $year = $date->format('Y');
        
        $existingEntry = StudentDetail::where('student_id', $request->student_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->first();

        if ($existingEntry) {
            return $this->errorResponse('Entry for this student already exists for this month.', 409);
        }

        $studentDetail = StudentDetail::create($request->all());
        return $this->successResponse($studentDetail, 'Student Detail created successfully', 201);
    }

    public function show($id)
    {
        try {
            // $studentDetail = StudentDetail::find($id);
            $studentDetail = StudentDetail::where('student_id', $id)->first();
            if(!$studentDetail){
                return $this->errorResponse('Student detail not found.', 404);
            }
            return $this->successResponse($studentDetail, 'Student detail retrieved successfully', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Student detail not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Show error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'total_day' => 'required|integer|min:0',
            'total_eat_day' => 'required|integer|min:0',
            'cut_day' => 'required|integer|min:0',
            // 'amount' => 'numeric|min:0',
            'date' => 'required|date',
            'simple_guest' => 'integer|min:0',
            'simple_guest_amount' => 'numeric|min:0',
            'feast_guest' => 'integer|min:0',
            'feast_guest_amount' => 'numeric|min:0',
            // 'due_amount' => 'numeric|min:0',
            // 'penalty_amount' => 'numeric|min:0',
            // 'total_amount' => 'numeric|min:0',
            // 'paid_amount' => 'numeric|min:0',
            // 'remain_amount' => 'numeric|min:0',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }
        
        $studentDetail = StudentDetail::find($id);

        if (!$studentDetail) {
            return $this->errorResponse('Student detail not found.', 404);
        }

        $studentDetail->update($request->all());
        return $this->successResponse($studentDetail, 'Student detail updated successfully');
    }

    public function destroy($id)
    {
        $studentDetail = StudentDetail::find($id);
        if(!$studentDetail){
            return $this->errorResponse('Student detail not found.', 404);
        }
        $studentDetail->delete();
        return $this->successResponse(null, 'Student detail deleted successfully');
    }

    public function generateBill()
    {
        $month = request()->query('month');
        $year = request()->query('year'); 
        // $startOfMonth = Carbon::createFromDate($year ?? now()->year, $month ?? now()->month, 1)->startOfMonth();
        // $endOfMonth = Carbon::createFromDate($year ?? now()->year, $month ?? now()->month, 1)->endOfMonth();
        $startOfMonth = Carbon::createFromDate($year ?? now()->year, $month ?? now()->month, 1)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromDate($year ?? now()->year, $month ?? now()->month, 1)->endOfMonth()->toDateString();

        $totalCost = Expense::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount');

        if ($totalCost === 0) {
            return $this->errorResponse('Please add expense details for the selected month.', 400);
        }

        $result = StudentDetail::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->selectRaw('SUM(total_eat_day) as total_eaten_days, SUM(simple_guest_amount) as total_simple_guest_amount, SUM(feast_guest_amount) as total_feast_guest_amount')
            ->first();

        if (empty($result) || $result->total_eaten_days === null) {
            return $this->errorResponse('Please add student details for the selected month.', 400);
        }

        $totalEatenDays = $result->total_eaten_days;
        $totalSimpleGuestAmount = $result->total_simple_guest_amount;
        $totalFeastGuestAmount = $result->total_feast_guest_amount;

        $totalGuestAmount = $totalSimpleGuestAmount + $totalFeastGuestAmount;

        $rate = $totalEatenDays > 0 ? round($totalCost  / $totalEatenDays, 2) : 0;
        $rateWithGuest = $totalEatenDays > 0 ? round(($totalCost  - $totalGuestAmount) / $totalEatenDays, 2) : 0;

        // Check if any student detail for the current month is already generated and locked
        $existingBill = StudentDetail::whereBetween('date', [$startOfMonth , $endOfMonth])
            ->whereIn('status', ['generated', 'lock'])
            ->first();

        if ($existingBill) {
            $response = [
                'rate' => $existingBill->rate,
                'status' => $existingBill->status,
            ];
            return $this->successResponse($response, 'Bill rate already generated for the selected month.', 200);
        } else {
            StudentDetail::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->update(['rate' => $rate, 'rate_with_guest' => $rateWithGuest, 'status' => 'pending']);

            $studentDetails = StudentDetail::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

            $status = !empty($studentDetails[0]['status']) ? $studentDetails[0]['status'] : 'pending';

            $response = [
                'rate' => $rate,
                'status' => $status,
            ];

            return $this->successResponse($response, 'Bill rate generated successfully for the selected month.', 200);
        }
    }

    public function updateGeneratedBill(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0',
            'status' => 'required|in:pending,generated,lock',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000',
        ]);

        $rate = $request->rate;
        $status = $request->status;
        $month = $request->month;
        $year = $request->year;

        $currentMonthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $currentMonthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $studentDetails = StudentDetail::whereBetween('date', [$currentMonthStart, $currentMonthEnd])->get();

        if ($studentDetails->isEmpty()) {
            return $this->errorResponse('Student detail not found for current month.', 404);
        }

        foreach ($studentDetails as $student) {
            $previousMonthDate = Carbon::createFromDate($year, $month, 1)->subMonth();
            $previousDetail = StudentDetail::where('student_id', $student->student_id)
                ->whereYear('date', $previousMonthDate->year)
                ->whereMonth('date', $previousMonthDate->month)
                ->first();

            $simple_guest_amount = $student->simple_guest_amount ?? 0;
            $feast_guest_amount = $student->feast_guest_amount ?? 0;
            // $remain_amount = $student->remain_amount ?? 0;
            $panelty_amount = $student->panelty_amount ?? 0;

            $previousMonthRemain = !empty($previousDetail->remain_amount) ? $previousDetail->remain_amount : 0;

            $total_amount = ($student->total_eat_day * $rate) + $simple_guest_amount + $feast_guest_amount + $panelty_amount + $previousMonthRemain;
            $amount = $student->total_eat_day * $rate;

            $student->update([
                'rate' => $rate,
                'amount' => $amount,
                'total_amount' => $total_amount,
                'due_amount' => $previousMonthRemain,
                'status' => $status === 'lock' ? 'lock' : $status,
            ]);
        }

        if ($status === 'lock') {
            $summary = StudentDetail::whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                ->selectRaw('
                    SUM(total_eat_day) as current_month_total_eat_day,
                    SUM(cut_day) as current_month_total_cut_day,
                    SUM(total_day) as current_month_total_day,
                    SUM(simple_guest_amount) as simple_guest_amount,
                    SUM(feast_guest_amount) as feast_guest_amount,
                    SUM(amount) as total_amount,
                    SUM(paid_amount) as total_collection,
                    SUM(remain_amount) as current_month_total_remaining
                ')
                ->first();

            $totalDeposit = Student::sum('deposit');

            $currentMonthExpense = Expense::whereBetween('date', [$currentMonthStart, $currentMonthEnd])->sum('amount');

            $previousMonthDate = Carbon::createFromDate($year, $month, 1)->subMonth();
            $previousTransaction = MonthlyTransaction::whereYear('bill_date', $previousMonthDate->year)
                ->whereMonth('bill_date', $previousMonthDate->month)
                ->first();

            $previousMonthTotalCollection = $previousTransaction->current_total_collection ?? 0;
            $previousMonthTotalCaseOnHand = $previousTransaction->current_month_total_cash_on_hand ?? 0;
            $previousMonthTotalCashGuestAmount = $previousTransaction->current_month_total_guest_amount ?? 0;

            $totalCashGuestAmount = $request->guest_cash ?? 0;
            $currentMonthCollectionAmount = $summary->total_collection;
            $currentMonthCashOnHand = ($previousMonthTotalCollection + $previousMonthTotalCaseOnHand + $previousMonthTotalCashGuestAmount) - $currentMonthExpense;
            $previousMonthTotalAmount = $previousMonthTotalCollection + $previousMonthTotalCaseOnHand + $previousMonthTotalCashGuestAmount;
            $total_amount = $summary->total_amount + $totalCashGuestAmount + $currentMonthCashOnHand;
            $profit = $total_amount - ($totalDeposit ?? 0);

            $existingTransaction = MonthlyTransaction::where('year', $year)
                ->where('month', $month)
                ->first();

            if (!$existingTransaction) {
                MonthlyTransaction::create([
                    'bill_date'                         => Carbon::createFromDate($year, $month, 1)->toDateString(),
                    'year'                              => $year,
                    'month'                             => $month,
                    'current_month_expense'             => $currentMonthExpense,
                    'current_total_collection'          => $currentMonthCollectionAmount,
                    'current_month_total_guest_amount'  => $totalCashGuestAmount,
                    'current_month_total_cash_on_hand'  => $currentMonthCashOnHand,
                    'current_month_total_amount'        => $total_amount,
                    'current_total_remaining'           => $summary->current_month_total_remaining,
                    'current_month_total_eat_day'       => $summary->current_month_total_eat_day,
                    'current_month_total_cut_day'       => $summary->current_month_total_cut_day,
                    'current_month_total_day'           => $summary->current_month_total_day,
                    'last_month_total_collection'       => $previousMonthTotalCollection,
                    'last_month_total_case_on_hand'     => $previousMonthTotalCaseOnHand,
                    'last_month_total_cash_guest_amount'=> $previousMonthTotalCashGuestAmount,
                    'last_month_total_amount'           => $previousMonthTotalAmount,
                    'current_month_profit'              => $profit
                ]);
            } else {
                $existingTransaction->update([
                    'current_month_expense'             => $currentMonthExpense,
                    'current_total_collection'          => $currentMonthCollectionAmount,
                    'current_month_total_guest_amount'  => $totalCashGuestAmount,
                    'current_month_total_cash_on_hand'  => $currentMonthCashOnHand,
                    'current_month_total_amount'        => $total_amount,
                    'current_total_remaining'           => $summary->current_month_total_remaining,
                    'current_month_total_eat_day'       => $summary->current_month_total_eat_day,
                    'current_month_total_cut_day'       => $summary->current_month_total_cut_day,
                    'current_month_total_day'           => $summary->current_month_total_day,
                    'last_month_total_collection'       => $previousMonthTotalCollection,
                    'last_month_total_case_on_hand'     => $previousMonthTotalCaseOnHand,
                    'last_month_total_cash_guest_amount'=> $previousMonthTotalCashGuestAmount,
                    'last_month_total_amount'           => $previousMonthTotalAmount,
                    'current_month_profit'              => $profit
                ]);
            }
        }

        return $this->successResponse($studentDetails, 'Bill updated successfully for the selected month.');
    }


    public function getMonthlyTransaction(Request $request)
    {
        $request->validate([
            'year'  => 'required|numeric',
            'month' => 'required|numeric|min:1|max:12',
        ]);

        $year  = $request->year;
        $month = $request->month;

        $transaction = MonthlyTransaction::where('year', $year)->where('month', $month)->first();

        if (!$transaction) {
            return $this->errorResponse('Monthly transaction not found for selected month.', 404);
        }

        return $this->successResponse($transaction, 'Monthly Transaction retrieved successfully');
    }

}