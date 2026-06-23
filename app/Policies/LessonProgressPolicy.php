<?php

namespace App\Policies;

use App\Models\User;

class LessonProgressPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }
}
