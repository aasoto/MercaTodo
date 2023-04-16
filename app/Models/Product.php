<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'products_category_id',
        'barcode',
        'description',
        'price',
        'unit',
        'stock',
        'picture_1',
        'picture_2',
        'picture_3',
        'availability'
    ];
}
