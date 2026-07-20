<?php

namespace App\Models;

use App\Events\NotificationSent;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    // Define which fields can be filled in using mass assignment
    protected $fillable = [
        'user_id',
        'target_role',
        'title',
        'message',
        'is_read',
    ];

    // Cast fields automatically
    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Notification belongs to a user (if targeted to a specific user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper method to create a notification and broadcast it.
     * This is clean, centralized, and easy for a beginner to understand.
     */
    public static function send($userId, $targetRole, $title, $message)
    {
        $notification = self::create([
            'user_id' => $userId,
            'target_role' => $targetRole,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);

        // Broadcast event for real-time notification
        event(new NotificationSent($notification));

        return $notification;
    }
}
