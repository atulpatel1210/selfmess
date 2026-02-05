<?php

namespace App\Http\Controllers;

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
            'student_id' => 'nullable|exists:students,id',
            'title'      => 'required|string',
            'body'       => 'required|string',
            'type'       => 'required|string',
        ]);

        // CASE 1: student_id pass che → single student
        if ($request->filled('student_id')) {

            $studentData = \App\Models\Student::with('user')->find($request->student_id);

            if (!$studentData || !$studentData->user) {
                return $this->errorResponse('Student or User not found.', 409);
            }

            $notification = \App\Models\Notification::create([
                'student_id' => $studentData->id,
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
                        'notification_id' => (string) $notification->id,
                    ],
                    false
                );
            }

            return $this->successResponse($notification, 'Notification sent to student', 201);
        }

        // CASE 2: student_id nathi → badha students
        $students = \App\Models\Student::with('user')
            ->whereHas('user', function ($q) {
                $q->whereNotNull('fcm_token');
            })
            ->get();

        foreach ($students as $student) {

            $notification = \App\Models\Notification::create([
                'student_id' => $student->id,
                'type'       => $request->type,
                'title'      => $request->title,
                'body'       => $request->body,
                'payload'    => $request->payload ?? [],
            ]);

            $this->sendFirebaseNotification(
                $student->user->fcm_token,
                $request->title,
                $request->body,
                [
                    'type' => $request->type,
                    'notification_id' => (string) $notification->id,
                ],
                false
            );
        }

        return $this->successResponse([], 'Notification sent to all students', 201);
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