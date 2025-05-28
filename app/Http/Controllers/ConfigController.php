<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class ConfigController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $configs = Config::all();
            return $this->successResponse($configs, 'Configurations retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Index error: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'config_key' => 'required|string|unique:configs',
            'config_value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        try {
            $config = Config::create($request->only(['config_key', 'config_value']));
            return $this->successResponse($config, 'Configuration created successfully.', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Store error: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $config = Config::find($id);
            if (!$config) {
                return $this->errorResponse('Configuration not found.', 404);
            }
            return $this->successResponse($config, 'Configuration retrieved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Show error: ' . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'config_key' => 'required|string|unique:configs,config_key,' . $id,
            'config_value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation error', 422, $validator->errors());
        }

        try {
            $config = Config::find($id);
            if (!$config) {
                return $this->errorResponse('Configuration not found.', 404);
            }

            $config->update($request->only(['config_key', 'config_value']));
            return $this->successResponse($config, 'Configuration updated successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Update error: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $config = Config::find($id);
            if (!$config) {
                return $this->errorResponse('Configuration not found.', 404);
            }

            $config->delete();
            return $this->successResponse(null, 'Configuration deleted successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse('Delete error: ' . $e->getMessage(), 500);
        }
    }
}