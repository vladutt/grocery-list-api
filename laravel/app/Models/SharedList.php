<?php

namespace App\Models;

use App\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedList extends Model
{
    use HasFactory, CurrentUser;
}
