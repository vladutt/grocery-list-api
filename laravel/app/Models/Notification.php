<?php

namespace App\Models;

use App\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static currentUser()
 * @method static singleNotification($icon, $title, $description)
 */
class Notification extends Model
{
    use HasFactory, CurrentUser;

    protected $guarded = [];

    public function singleNotification(string $icon, string $title, string $description): void
    {
        $this->create([
           'icon' => $icon,
           'title' => $title,
           'description' => $description,
        ]);
    }
}
