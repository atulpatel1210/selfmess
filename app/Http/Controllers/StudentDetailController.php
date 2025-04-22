<?php

namespace App\Http\Controllers;

use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use App\Models\Expense;
use Carbon\Carbon;

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
    
    public function generateBill(Request $request)
    {
        // $lastMonthGuestCash = $request->input('last_month_guest_cash');
        // $lastMonthCashOnHand = $request->input('last_month_cash_on_hand');
        // $lastMonthCollection = $request->input('last_month_collection');
        // $currentMonthGuestCash = $request->input('current_month_guest_cash');

        if (!$totalEatenDays || !$lastMonthGuestCash || !$lastMonthCashOnHand || !$lastMonthCollection || !$currentMonthGuestCash) {
            return response()->json(['message' => 'Please provide all required parameters.'], 400);
        }

        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        $currentMonthTotalCost = Expense::whereBetween('date', [$currentMonthStart, $currentMonthEnd])->sum('amount');

        if ($currentMonthTotalCost === 0) {
            return response()->json(['message' => 'Please add expense details for the current month.'], 400);
        }

        $totalEatenDays = StudentDetail::whereBetween('date', [$currentMonthStart, $currentMonthEnd])->sum('total_eat_day');

        if ($totalEatenDays === 0) {
            return response()->json(['message' => 'Please add student details for the current month.'], 400);
        }

        $lastMonthTotalCash = $lastMonthGuestCash + $lastMonthCashOnHand + $lastMonthCollection;
        $marchCashOnHand = $lastMonthTotalCash - $currentMonthTotalCost;
        $currentMonthBillWithGuest = ($currentMonthTotalCost - $currentMonthGuestCash) / $totalEatenDays;
        $currentMonthBillWithoutGuest = $currentMonthTotalCost / $totalEatenDays;

        $response = [
            'costs' => [
                'total_cost' => $currentMonthTotalCost,
            ],
            'last_month' => [
                'guest_cash' => $lastMonthGuestCash,
                'cash_on_hand' => $lastMonthCashOnHand,
                'collection' => $lastMonthCollection,
                'total_cash' => $lastMonthTotalCash,
            ],
            'march_cash_on_hand' => $marchCashOnHand,
            'current_month_guest_cash' => $currentMonthGuestCash,
            'current_month_bill_with_guest' => $currentMonthBillWithGuest,
            'current_month_bill_without_guest' => $currentMonthBillWithoutGuest,
        ];

        return response()->json($response);
    }
}