<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.fcm.api_key');
    }

    public function sendPushNotification(
        string $deviceToken,
        string $title,
        string $body
    ) {
        // $url = 'https://fcm.googleapis.com/v1/projects/mychatapppushnotif/messages:send';
        $url = 'https://fcm.googleapis.com/fcm/send';

        Log::info("Sending notification to url: {$url}");
        Log::info("API Key: {$this->apiKey}");

        $payload = [
            'to' => $deviceToken,
            'title' => $title,
            'body' => $body
        ];

        $response = Http::withHeaders([
            'Authorization' => "key={$this->apiKey}",
            'Content-Type' => 'application/json'
        ])->post($url, $payload);

        Log::info($response->body());

        return $response->json();
    }

    public function sendPushNotificationUsingServiceAccount(
        string $deviceToken,
        string $title,
        string $body
    ) {
        $firebaseMessagingUrl = 'https://www.googleapis.com/auth/firebase.messaging';
        $serviceAccountContent = json_decode(env('FIREBASE_CREDENTIALS_STRING'), true);
        // $serviceAccountPath = storage_path('app/firebase/mychatapppushnotif-firebase-adminsdk-fbsvc-22e5722d03.json');
        // $serviceAccountContent = json_decode(file_get_contents($serviceAccountPath), true);

        $credentials = new ServiceAccountCredentials(
            $firebaseMessagingUrl,
            $serviceAccountContent
        );

        $accessToken = $credentials->fetchAuthToken()['access_token'];

        $projectConfig = $serviceAccountContent;
        $projectId = $projectConfig['project_id'];

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'android' => [
                    'priority' => 'high',
                ],
            ],
        ];

        $url = 'https://fcm.googleapis.com/v1/projects/mychatapppushnotif/messages:send';
        $response = Http::withToken($accessToken)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($url, $payload);

        Log::info($response->body());

        return $response->json();
    }
}
