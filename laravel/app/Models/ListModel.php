<?php

namespace App\Models;

use App\Traits\CurrentUser;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ListModel extends Model
{
    use CurrentUser;

    protected $table = 'lists';

    public function items() {
        return $this->hasMany(Item::class, 'list_id', 'id');
    }

    public function sharedLists() {
        return $this->hasMany(SharedList::class, 'list_id', 'id');
    }

//    public function totalItems():Attribute {
//        return Attribute::make(
//            get: fn () => $this->items()->count()
//        );
//    }
}
