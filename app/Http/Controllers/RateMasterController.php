<?php

namespace App\Http\Controllers;

use App\Models\RateMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class RateMasterController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $rateMasters = RateMaster::all();
        return $this->successResponse($rateMasters, 'Rate Masters retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric',
            'simple_guest_rate' => 'numeric',
            'feast_guest_rate' => 'numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $existingRate = RateMaster::first();
        if ($existingRate) {
            return $this->errorResponse('This rate master already exists.', 409);
        }

        $rateMaster = RateMaster::create($request->all());
        return $this->successResponse($rateMaster, 'Rate Master created successfully', 201);
    }

    public function show(RateMaster $rateMaster)
    {
        return $this->successResponse($rateMaster, 'Rate Master retrieved successfully');
    }

    public function update(Request $request, RateMaster $rateMaster)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'numeric',
            'simple_guest_rate' => 'numeric',
            'feast_guest_rate' => 'numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $rateMaster->update($request->all());
        return $this->successResponse($rateMaster, 'Rate Master updated successfully');
    }

    public function destroy(RateMaster $rateMaster)
    {
        $rateMaster->delete();
        return $this->successResponse(null, 'Rate Master deleted successfully');
    }
}