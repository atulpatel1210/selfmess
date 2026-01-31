<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Traits\FirebaseNotification;

class NotificationController extends Controller
{
    use ApiResponse, FirebaseNotification;

    public function getStudentNotifications($student_id = null)
    {
        $notifications = Notification::with('student.user')
        ->when($student_id, function ($query, $student_id) {
            return $query->where('student_id', $student_id);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return $this->successResponse($notifications, 'Notifications retrieved successfully', 201);
    }

    public function sendCustomNotification(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title'      => 'required|string',
            'body'       => 'required|string',
            'type'       => 'required|string',
        ]);

        $studentData = \App\Models\Student::with('user')->find($request->student_id);

        if (!$studentData || !$studentData->user) {
            return $this->errorResponse('Student or User not found.', 409);
        }

        $notification = \App\Models\Notification::create([
            'student_id' => $request->student_id,
            'type'       => $request->type,
            'title'      => $request->title,
            'body'       => $request->body,
            'payload'    => $request->payload ?? [],
        ]);

        if ($studentData->user->fcm_token) {
            $this->sendFirebaseNotification(
                $studentData->user->fcm_token,
                $request->title,
                $request->body,
                [
                    'type' => $request->type,
                    'notification_id' => (string)$notification->id
                ],
                false // isTopic = false (Web/Individual)
            );
        }
        return $this->successResponse($notification, 'Notification sent and stored successfully', 201);
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification) {
            $notification->update(['is_read' => true]);
            return $this->successResponse($notification, 'Marked as read', 201);
        }
        return $this->errorResponse('Notification not found.', 409);
    }
}