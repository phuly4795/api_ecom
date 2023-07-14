<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['code', 'name','price', 'image','quatity', 'category_id', 'mota','created_by', 'updated_by', 'deleted_by'];

    public function category(){
        return $this->belongsTo(category::class,);
    }

}
