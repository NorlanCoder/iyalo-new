<?php

namespace App\Service;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\LaravelFirebase\Facades\Firebase;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    
    public function sendNotificationAtVisitor()
    {
        $pending = Notification::where('role','visitor')->where('is_push',false);
        $notifications = $pending->get();
        $tokens = User::where('role','visitor')->pluck('token_notify');
        $pending->update([
            'is_push' =>true,]
        );

        foreach ($notifications as $notification) {
            $message = CloudMessage::withTarget('token', $tokens)
            ->withNotification([
                'title' => $notification->title,
                'body' => $notification->body,
            ]);
    
            Firebase::messaging()->send($message);
        }
        return true;

    }

    public function sendNotificationVisit($id, $title, $body){
        $user = User::find($id);

        $message = CloudMessage::withTarget('token', $user->token_notify)
        ->withNotification([
            'title' => $title,
            'body' => $body,
        ]);

        Firebase::messaging()->send($message);

        $notification = Notification::create([
            'title' => $title,
            'body' => $body,
            'role' => 'announcer',
            'is_push' => true,
            'user_id' => $id,
        ]);

        return true;
    }

}