<?php

namespace App\Traits;

/**
 * Trait CurrentUser
 *
 * @method currentUser()
 *
 * @package App\Traits
 */
trait CurrentUser
{
    public function scopeCurrentUser($query) {
        return $query->where('user_id', auth()->user()->id);
    }
}
