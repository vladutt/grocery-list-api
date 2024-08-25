<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsList extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'items_list';
}
