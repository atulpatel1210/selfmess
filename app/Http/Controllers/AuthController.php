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
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,secretary,staff,student',
            'hostel_name' => 'required_if:role,student',
            'room_no' => 'required_if:role,student',
            'currently_pursuing' => 'required_if:role,student',
            'currently_studying_year' => 'required_if:role,student|integer',
            'date' => 'required_if:role,student|date',
            'year' => 'required_if:role,student|integer',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors()->toArray());
        }

        try {
            $role = Role::where('name', $request->role)->firstOrFail();

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => Hash::make($request->input('password')),
                'role_id' => $role->id,
            ]);

            if ($request->role === 'student') {
                Student::create([
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
            }
            return $this->successResponse($user, 'User registered successfully', 201);
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse('Role not found.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = [];

        if ($request->role === 'student') {
            $credentials['mobile'] = $request->input('mobile');
            $credentials['password'] = $request->input('password');
        } else {
            $credentials['email'] = $request->input('email');
            $credentials['password'] = $request->input('password');
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            return $this->successResponse([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Login successful', 200);
        }

        return $this->errorResponse('Invalid credentials', 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->successResponse(null, 'Logged out successfully', 200);
    }
}