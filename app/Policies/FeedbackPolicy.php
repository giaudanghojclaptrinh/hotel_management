<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Feedback;

class FeedbackPolicy
{
    /**
     * Determine whether the user can view any feedbacks.
     */
    public function viewAny(?User $user)
    {
        if (! $user) return false;
        // Support both 'is_admin' boolean or 'role' string
        return ($user->is_admin ?? false) || (isset($user->role) && $user->role === 'admin');
    }

    /**
     * Determine whether the user can view the feedback.
     */
    public function view(?User $user, Feedback $feedback)
    {
        if (! $user) return false;
        return ($user->is_admin ?? false) || (isset($user->role) && $user->role === 'admin');
    }

    /**
     * Determine whether the user can update the feedback.
     */
    public function update(?User $user, Feedback $feedback)
    {
        if (! $user) return false;
        return ($user->is_admin ?? false) || (isset($user->role) && $user->role === 'admin');
    }
}
