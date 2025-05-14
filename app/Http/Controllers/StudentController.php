<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Database\QueryException;

class StudentController extends Controller
{
    use ApiResponse;

    public function dashboard()
    {
        try {
            return $this->successResponse('Dashboard');
        } catch (\Exception $e) {
            return $this->errorResponse('Dashboard error: ' . $e->getMessage(), 500);
        }
    }

    public function index()
    {
        try {
            $students = Student::all();
            return $this->successResponse($students, 'Students retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Index error: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'hostel_name' => 'required',
            'room_no' => 'required',
            'email' => 'required|email|unique:students',
            'residential_address' => 'nullable',
            'currently_pursuing' => 'required',
            'currently_studying_year' => 'required|integer',
            'date' => 'required|date',
            'year' => 'required|integer',
            'mobile' => 'required|unique:students',
            'alternative_mobile' => 'nullable',
            'advisor_guide' => 'nullable',
            'blood_group' => 'nullable',
            'deposit' => 'nullable|numeric',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        $role = Role::where('name', 'student')->first();
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => Hash::make($request->input('password')),
                'role_id' => $role->id,
            ]);

            $student = Student::create([
                'name' => $request->input('name'),
                'hostel_name' => $request->input('hostel_name'),
                'room_no' => $request->input('room_no'),
                'email' => $request->input('email'),
                'residential_address' => $request->input('residential_address'),
                'currently_pursuing' => $request->input('currently_pursuing'),
                'currently_studying_year' => $request->input('currently_studying_year'),
                'date' => $request->input('date'),
                'year' => $request->input('year'),
                'mobile' => $request->input('mobile'),
                'alternative_mobile' => $request->input('alternative_mobile'),
                'advisor_guide' => $request->input('advisor_guide'),
                'blood_group' => $request->input('blood_group'),
                'deposit' => $request->input('deposit'),
                'user_id' => $user->id,
            ]);
            return $this->successResponse($student, 'Student created successfully', 201);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->errorResponse('Duplicate entry. Email or mobile already exists.', 422);
            }
            return $this->errorResponse('Internal server error', 500);
        }
    }

    public function show($id)
    {
        try {
            $student = Student::find($id);
            if(!$student){
                return $this->errorResponse('Student not found.', 404);
            }
            return $this->successResponse($student, 'Student retrieved successfully', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Student not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Show error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'hostel_name' => 'required',
            'room_no' => 'required',
            'email' => 'required|email',
            'residential_address' => 'nullable',
            'currently_pursuing' => 'required',
            'currently_studying_year' => 'required|integer',
            'date' => 'required|date',
            'year' => 'required|integer',
            'alternative_mobile' => 'nullable',
            'advisor_guide' => 'nullable',
            'blood_group' => 'nullable',
            'deposit' => 'nullable|numeric',
            'password' => 'nullable|min:6',
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }
        try {
            $student = Student::find($id);
            if (!$student) {
                return $this->errorResponse('Student not found.', 404);
            }
    
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:students,email,' . $student->id,
                // 'mobile' => 'required|unique:students,mobile,' . $student->id,
            ]);
    
            if ($validator->fails()) {
                return $this->errorResponse('Validation error', 422, $validator->errors());
            }
    
            $student->update($request->all());
            $user = User::where('id', $student->user_id)->first();
            $user->password = Hash::make($request->input('password'));
            $user->update();
            return $this->successResponse($student, 'Student updated successfully', 200);
        }
        catch (ModelNotFoundException $e) {
            return $this->errorResponse('Student not found.', 404);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return $this->errorResponse('Duplicate entry. Email or mobile already exists.', 422);
            }
            return $this->errorResponse('Database error: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            return $this->errorResponse('Update error: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::find($id);
            if (!$student) {
                return $this->errorResponse('Student not found.', 404);
            }
            $student->delete();
            return $this->successResponse(null, 'Student deleted successfully', 200);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Student not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Destroy error: ' . $e->getMessage(), 500);
        }
    }
}