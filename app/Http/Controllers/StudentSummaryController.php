<?php

namespace App\Http\Controllers;

use App\Models\StudentSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;
use App\Models\StudentAttendance;
use App\Models\RateMaster;

class StudentSummaryController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = StudentSummary::query();
            $month = $request->input('month');
            $year = $request->input('year');
            if ($year) {
                $query->whereYear('date', $year);
            } else {
                $query->whereYear('date', now()->year);
            }
            if ($month) {
                $query->whereMonth('date', $month);
            } else {
                $query->whereMonth('date', now()->month);
            }
            $studentId = $request->input('student_id');
            if ($studentId) {
                $query->where('student_id', $studentId);
            }
            $summaries = $query->get();
            return $this->successResponse($summaries, 'Student summaries retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Index error: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'total_day' => 'required|integer',
            'eat_day' => 'required|integer',
            'cut_day' => 'required|integer',
            'simple_guest' => 'nullable|integer',
            'simple_guest_charge' => 'nullable|numeric',
            'feast_guest' => 'nullable|integer',
            'feast_guest_charge' => 'nullable|numeric',
            'due_amount' => 'nullable|numeric',
            'penalty_amount' => 'nullable|numeric',
            'total_bill' => 'nullable|numeric',
            'paid_bill' => 'nullable|numeric',
            'remain_amount' => 'nullable|numeric',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }
        try {
            $summary = StudentSummary::create($request->all());
            return $this->successResponse($summary, 'Student summaries created successfully', 201);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->errorResponse('Duplicate entry. Email or mobile already exists.', 422);
            }
            return $this->errorResponse('Internal server error', 500);
        }
    }

    public function show(StudentSummary $summary)
    {
        try {
            return $this->successResponse($summary, 'Student summaries retrieved successfully', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Student summaries not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Show error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, StudentSummary $summary)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'total_day' => 'required|integer',
            'eat_day' => 'required|integer',
            'cut_day' => 'required|integer',
            'simple_guest' => 'nullable|integer',
            'simple_guest_charge' => 'nullable|numeric',
            'feast_guest' => 'nullable|integer',
            'feast_guest_charge' => 'nullable|numeric',
            'due_amount' => 'nullable|numeric',
            'penalty_amount' => 'nullable|numeric',
            'total_bill' => 'nullable|numeric',
            'paid_bill' => 'nullable|numeric',
            'remain_amount' => 'nullable|numeric',
            'remark' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }
        try {
            $summary->update($request->all());
            return $this->successResponse($summary, 'Student summaries updated successfully', 200);
        } 
        catch (ModelNotFoundException $e) {
            return $this->errorResponse('Student summaries not found.', 404);
        } catch (QueryException $e) {
            return $this->errorResponse('Database error: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            return $this->errorResponse('Update error: ' . $e->getMessage(), 500);
        }
    }

    public function destroy(StudentSummary $summary)
    {
        try {
            $summary = Student::find($id);
            if (!$summary) {
                return $this->errorResponse('Student not found.', 404);
            }
            $summary->delete();
            return $this->successResponse(null, 'Student summaries deleted successfully', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Student summaries not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Destroy error: ' . $e->getMessage(), 500);
        }
    }

    public function generateMonthlySummaries(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'month' => 'required|integer|min:1|max:12',
                'year' => 'required|integer',
            ]);

            $month = $validatedData['month'];
            $year = $validatedData['year'];

            $startDate = date('Y-m-d', strtotime("$year-$month-01"));
            $endDate = date('Y-m-t', strtotime("$year-$month-01"));

            $rate = RateMaster::first();

            $studentIds = StudentAttendance::whereBetween('attendance_date', [$startDate, $endDate])
                ->distinct()
                ->pluck('student_id');

            foreach ($studentIds as $studentId) {
                $attendances = StudentAttendance::where('student_id', $studentId)
                    ->whereBetween('attendance_date', [$startDate, $endDate])
                    ->get();

                $totalDays = date('t', strtotime($startDate));
                $eatDays = $attendances->where('is_present', true)->count();
                $cutDays = $totalDays - $eatDays;
                $simpleGuestCount = $attendances->sum('simple_guest_count');
                $feastGuestCount = $attendances->sum('feast_guest_count');

                $studentCharges = $attendances->sum('student_charge');
                $simpleGuestCharge = $simpleGuestCount * $rate->simple_guest_rate;
                $feastGuestCharge = $feastGuestCount * $rate->feast_guest_rate;
                $total_bill = $studentCharges + $simpleGuestCharge + $feastGuestCharge;

                StudentSummary::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'date' => date('Y-m-d', strtotime("$year-$month-01")),
                    ],
                    [
                        'total_day' => $totalDays,
                        'eat_day' => $eatDays,
                        'cut_day' => $cutDays,
                        'student_charge' => $studentCharges,
                        'simple_guest' => $simpleGuestCount,
                        'simple_guest_charge' => $simpleGuestCharge,
                        'feast_guest' => $feastGuestCount,
                        'feast_guest_charge' => $feastGuestCharge,
                        'due_amount' => 0,
                        'penalty_amount' => 0,
                        'total_bill' => $total_bill,
                        'paid_bill' => 0,
                        'remain_amount' => $total_bill,
                        'remark' => "Summary for $year-$month",
                    ]
                );
                // $student = Student::where('id', $studentId)->first();
                // if ($student && $student->user && $student->user->device_token) {
                //     $message = CloudMessage::withTarget('token', $student->user->device_token)
                //         ->withNotification([
                //             'title' => 'Monthly Bill',
                //             'body' => "Your monthly bill for $year-$month is ready. Total bill: ₹".$total_bill,
                //         ]);
                //     $firebase->send($message);
                // }
            }
            //composer require kreait/firebase-php

            return $this->successResponse(null, 'Monthly summaries generated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorResponse('Summary generation error: ' . $e->getMessage(), 500);
        }
    }
}