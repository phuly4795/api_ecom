<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['code', 'name','created_by', 'updated_by', 'deleted_by'];


    public function product(){
        return $this->hasMany(Product::class,'id', 'category_id');
    }

}
