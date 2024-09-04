<?php

namespace App\Service;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    
    public function sendNotificationAtVisitor()
    {
        Log::info('Start Push');
        DB::beginTransaction();
            $pending = Notification::where('role','visitor')->where('is_push',false);
            $notifications = $pending->get();
            $tokens = User::where('role','visitor')->where('token_notify','!=','')->pluck('token_notify');
            $pending->update([
                'is_push' =>true,]
            );

            foreach ($notifications as $notification) {
                foreach ($tokens as $token) {
                    $message = CloudMessage::withTarget('token', $token)
                    ->withNotification([
                        'title' => $notification->title,
                        'body' => $notification->body,
                    ])->withData([
                        'id' => $notification->id,
                    ]);
                    Firebase::messaging()->send($message);
                }
            }
        DB::commit();
        Log::info('End Push');

        return true;

    }

    public function sendNotificationVisit($id, $title, $body){
        $user = User::find($id);

        $notification = Notification::create([
            'title' => $title,
            'body' => $body,
            'role' => 'announcer',
            'is_push' => true,
            'user_id' => $id,
        ]);

        $message = CloudMessage::withTarget('token', $user->token_notify)
        ->withNotification([
            'title' => $title,
            'body' => $body,
        ])->withData([
            'id' => $notification->id,
        ]);

        Firebase::messaging()->send($message);

        return true;
    }


}