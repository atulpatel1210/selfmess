<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $year = $request->query('year');
            $month = $request->query('month');

            $query = Expense::query();
            
            if ($request->has('year')) {
                $year = $request->query('year');
                $query->whereYear('date', $year);
            }
            
            if ($request->has('month')) {
                $month = $request->query('month');
                $query->whereMonth('date', $month);
            }
            
            if (!$request->has('year') && !$request->has('month')) {
                $now = Carbon::now();
                $query->whereYear('date', $now->year)
                      ->whereMonth('date', $now->month);
            }

            // if ($year && $month) {
            //     $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            //     $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            //     $query->whereBetween('date', [$startDate, $endDate]);
            // } else {
            //     $now = Carbon::now();
            //     $startDate = $now->startOfMonth();
            //     $endDate = $now->endOfMonth();
            //     $query->whereBetween('date', [$startDate, $endDate]);
            // }

            $expenses = $query->get();
            return $this->successResponse($expenses, 'Expenses retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Index error: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $expense = Expense::create($request->all());
        return $this->successResponse($expense, 'Expense created successfully', 201);
    }

    public function show($id)
    {
        try {
            $expense = Expense::find($id);
            if(!$expense){
                return $this->errorResponse('Expense not found.', 404);
            }
            return $this->successResponse($expense, 'Expense retrieved successfully', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Expense not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Show error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'item' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }
        $expense = Expense::find($id);

        if (!$expense) {
            return $this->errorResponse('Expense not found.', 404);
        }
        $expense->update($request->all());
        return $this->successResponse($expense, 'Expense updated successfully');
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        if(!$expense){
            return $this->errorResponse('Expense not found.', 404);
        }
        $expense->delete();
        return $this->successResponse(null, 'Expense deleted successfully');
    }
}