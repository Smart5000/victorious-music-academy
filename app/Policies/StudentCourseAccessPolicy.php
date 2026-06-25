<?php

namespace App\Policies;

use App\Models\User;

class StudentCourseAccessPolicy
{
    public function before(User $user): ?bool
    {
        return $user->role === 'admin' ? true : null;
    }
}
