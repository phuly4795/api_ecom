<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['cart_id', 'product_code', 'quatity', 'price','created_by', 'updated_by', 'deleted_by'];

    // public function cart() {
    //     return $this->belongsTo(Cart::class,'id', 'cart_id');
    // }
}
