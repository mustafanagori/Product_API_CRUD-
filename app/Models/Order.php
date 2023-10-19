<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;
use App\Models\User;


class Order extends Model
{
    use HasFactory;
    public function carts(){
        return $this->hasMany(Cart::class,'order_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
