<?php

namespace App\Policies;

use App\Models\React;
use App\Models\User;

class ReactPolicy
{
    public function delete(User $user, React $react): bool
    {
        return $react->user_id === $user->id || $user->is_admin;
    }
}
