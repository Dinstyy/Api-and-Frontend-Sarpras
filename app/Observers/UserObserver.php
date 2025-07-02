<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        logActivity('create user', 'Membuat user: ' . $user->username);
    }

    public function updated(User $user)
    {
        logActivity('update user', 'Mengubah user: ' . $user->username);
    }

    public function deleted(User $user)
    {
        logActivity('delete user', 'Menghapus user: ' . $user->username);
    }
}
