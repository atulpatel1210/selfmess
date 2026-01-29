<?php

namespace App\Traits;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

trait FirebaseNotification
{
    /**
     * Send notification to a specific device token or a topic (e.g. mobile number)
     * 
     * @param string $target The token or topic name
     * @param string $title
     * @param string $body
     * @param array $data Additional data payload
     * @param bool $isTopic Whether the target is a topic
     */
    public function sendFirebaseNotification($target, $title, $body, $data = [], $isTopic = true)
    {
        try {
            $serviceAccountPath = config('services.firebase.credentials.file');
            
            if (!file_exists($serviceAccountPath)) {
                \Log::error("Firebase credentials file not found at: " . $serviceAccountPath);
                return false;
            }

            $factory = (new Factory)->withServiceAccount($serviceAccountPath);
            $messaging = $factory->createMessaging();

            $message = CloudMessage::withTarget($isTopic ? 'topic' : 'token', $target)
                ->withNotification(Notification::create($title, $body))
                ->withData($data);

            $messaging->send($message);
            return true;
        } catch (\Exception $e) {
            \Log::error("Firebase notification error: " . $e->getMessage());
            return false;
        }
    }
}
