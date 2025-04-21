<?php

namespace App\Http\Controllers;

use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class StudentAttendanceController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $attendances = StudentAttendance::with('student')->get();
            return $this->successResponse($attendances, 'Attendances retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Index error: ' . $e->getMessage(), 500);
        }
    }

    private function calculateGuestCharge($guestCount, $rate)
    {
        return ($guestCount ?? 0) * $rate;
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'student_id' => 'required|exists:students,id',
                'attendance_date' => 'required|date',
                'is_present' => 'required|boolean',
                'is_feast_day' => 'required|boolean',
                'simple_guest_count' => 'nullable|integer|min:0',
                'feast_guest_count' => 'nullable|integer|min:0',
                'remark' => 'nullable|string',
            ]);

            $rateMaster = RateMaster::firstOrFail(); // RateMaster ન મળે તો exception ફેંકે

            $validatedData['student_charge'] = $rateMaster->rate;
            $validatedData['simple_guest_charge'] = $this->calculateGuestCharge($validatedData['simple_guest_count'] ?? 0, $rateMaster->simple_guest_rate);
            $validatedData['feast_guest_charge'] = $this->calculateGuestCharge($validatedData['feast_guest_count'] ?? 0, $rateMaster->feast_guest_rate);

            $attendance = StudentAttendance::create($validatedData);
            return $this->successResponse($attendance, 'Attendance created successfully', 201);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Rate Master not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Store error: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $attendance = StudentAttendance::with('student')->findOrFail($id);
            return $this->successResponse($attendance, 'Attendance retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Attendance not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Show error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $attendance = StudentAttendance::findOrFail($id);

            $validatedData = $request->validate([
                'student_id' => 'exists:students,id',
                'attendance_date' => 'date',
                'is_present' => 'boolean',
                'is_feast_day' => 'boolean',
                'simple_guest_count' => 'integer|min:0',
                'feast_guest_count' => 'integer|min:0',
                'remark' => 'nullable|string',
            ]);

            if (isset($validatedData['simple_guest_count']) || isset($validatedData['feast_guest_count'])) {
                $rateMaster = RateMaster::firstOrFail(); // RateMaster ન મળે તો exception ફેંકે

                $validatedData['student_charge'] = $rateMaster->rate;

                $validatedData['simple_guest_charge'] = $this->calculateGuestCharge(
                    $validatedData['simple_guest_count'] ?? $attendance->simple_guest_count,
                    $rateMaster->simple_guest_rate
                );

                $validatedData['feast_guest_charge'] = $this->calculateGuestCharge(
                    $validatedData['feast_guest_count'] ?? $attendance->feast_guest_count,
                    $rateMaster->feast_guest_rate
                );
            }

            $attendance->update($validatedData);
            return $this->successResponse($attendance, 'Attendance updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Attendance not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Update error: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $attendance = StudentAttendance::findOrFail($id);
            $attendance->delete();
            return $this->successResponse(null, 'Attendance deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Attendance not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Destroy error: ' . $e->getMessage(), 500);
        }
    }
}