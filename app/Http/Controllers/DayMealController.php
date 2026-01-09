<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DayMeal;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;

class DayMealController extends Controller
{
    use ApiResponse;

    public function index()
    {
        try {
            $meals = DayMeal::orderByRaw("
                FIELD(day,'monday','tuesday','wednesday','thursday','friday','saturday','sunday')
            ")->get();

            return $this->successResponse(
                $meals,
                'Meals retrieved successfully'
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Index error: ' . $e->getMessage(),
                500
            );
        }
    }

    public function view($id)
    {
        try {
            $meal = DayMeal::find($id);

            if (!$meal) {
                return $this->errorResponse('Meal not found.', 404);
            }

            return $this->successResponse(
                $meal,
                'Meal retrieved successfully'
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                'View error: ' . $e->getMessage(),
                500
            );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'breakfast' => 'nullable|string',
                'lunch'     => 'nullable|string',
                'dinner'    => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse(
                    'Validation error',
                    422,
                    $validator->errors()
                );
            }

            $meal = DayMeal::find($id);

            if (!$meal) {
                return $this->errorResponse('Meal not found.', 404);
            }

            $meal->update(
                $request->only(['breakfast','lunch','dinner'])
            );

            return $this->successResponse(
                $meal,
                'Meal updated successfully'
            );

        } catch (\Exception $e) {
            return $this->errorResponse(
                'Update error: ' . $e->getMessage(),
                500
            );
        }
    }

}
