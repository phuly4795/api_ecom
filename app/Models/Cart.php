<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'total_price', 'address','created_by', 'updated_by', 'deleted_by'];
    
    public function details()
    {
        return $this->hasMany(CartDetail::class, 'cart_id', 'id');
    }
}
