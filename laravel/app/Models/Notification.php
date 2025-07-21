<?php

namespace App\Models;

use App\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static currentUser()
 * @method singleNotification($icon, $title, $description)
 */
class Notification extends Model
{
    use HasFactory, CurrentUser;

    protected $guarded = [];

    public static function singleNotification(string $icon, string $title, string $description): void
    {
        Notification::create([
           'icon' => $icon,
           'user_id' => auth()->user()->id,
           'title' => $title,
           'description' => $description,
        ]);
    }
}
