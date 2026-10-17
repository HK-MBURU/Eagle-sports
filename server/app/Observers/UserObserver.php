<?php

namespace App\Observers;

use App\Models\User;
use App\Models\AdminNotification;

class UserObserver
{
    public function created(User $user): void
    {
        AdminNotification::notify(
            type: 'new_user',
            title: 'New user registered',
            message: "{$user->name} ({$user->email}) just signed up",
            data: ['user_id' => $user->id, 'user_name' => $user->name, 'email' => $user->email],
            link: '/admin/users',
        );
    }
}
