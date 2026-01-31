<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentSummaryController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RateMasterController;
use App\Http\Controllers\StudentDetailController;
use App\Http\Controllers\DayMealController;
use App\Http\Controllers\DashboardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Admin Routes
    Route::middleware(['role:admin,secretary,staff'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard']);
        Route::get('/chart-stats', [DashboardController::class, 'getChartStats']);
        Route::apiResource('/students', StudentController::class);
        Route::apiResource('/expenses', ExpenseController::class);
        Route::apiResource('student-details', StudentDetailController::class);
        Route::get('/generate-bill', [StudentDetailController::class, 'generateBill']);
        Route::post('/update-generated-bill', [StudentDetailController::class, 'updateGeneratedBill']);
        Route::post('/update-remain-amount', [StudentDetailController::class, 'updateRemainAmount']);
        Route::post('/get-monthly-transaction', [StudentDetailController::class, 'getMonthlyTransaction']);
        Route::post('/bulk-store', [StudentDetailController::class, 'bulkStore']);
        Route::post('/delete-monthly-data', [StudentDetailController::class, 'deleteMonthlyData']);
        Route::get('/truncate-tables', [StudentDetailController::class, 'truncateAllTables']);
        Route::apiResource('/configs', ConfigController::class);
        Route::get('/day-meals', [DayMealController::class, 'index']);
        Route::get('/day-meals/{id}', [DayMealController::class, 'view']);
        Route::put('/day-meals/{id}', [DayMealController::class, 'update']);
        Route::post('/update-fcm-token', [StudentController::class, 'updateFcmToken']);
        Route::get('/notifications/{student_id?}', [NotificationController::class, 'getStudentNotifications']);
        Route::post('/read-notifications/{id}', [NotificationController::class, 'markAsRead']);
        Route::post('/send-notification', [NotificationController::class, 'sendCustomNotification']);

        // Route::apiResource('/summaries', StudentSummaryController::class);
        // Route::apiResource('attendances', StudentAttendanceController::class);
        // Route::post('/summaries/generate-monthly', [StudentSummaryController::class, 'generateMonthlySummaries']);
        // Route::apiResource('rate-masters', RateMasterController::class);
    });

    // Secretary Routes
    // Route::middleware('role:secretary')->prefix('secretary')->group(function () {
    //     Route::get('/dashboard', [StudentController::class, 'dashboard']);
    //     // Route::get('/summaries', [StudentSummaryController::class, 'index']);
    //     // Route::get('/summaries/{summary}', [StudentSummaryController::class, 'show']);
    // });

    // Staff Routes
    // Route::middleware('role:staff')->prefix('staff')->group(function () {
    //     Route::get('/dashboard', [StudentController::class, 'dashboard']);
    //     // Route::get('/students', [StudentController::class, 'index']);
    //     // Route::get('/students/{student}', [StudentController::class, 'show']);
    //     Route::apiResource('students', StudentController::class)->only(['index', 'show']);
    // });

    // Student Routes
    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard']);
        // Route::get('/summaries', [StudentSummaryController::class, 'index']);
        // Route::get('/summaries/{summary}', [StudentSummaryController::class, 'show']);
        // Route::apiResource('student-details', StudentDetailController::class);
    });
});