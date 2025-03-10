<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    // protected $fillable = [
    //     'name',
    //     'price',
    //     'short_description',
    //     'qty',
    //     'product_category_id',
    // ];

    protected $guarded = [];

    public function productCategory(){
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function images(){
        return $this->hasMany(ProductImage::class, 'product_id');
    }
}
