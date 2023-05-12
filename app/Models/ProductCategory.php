<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'products_categories';

    protected $fillable = ['name'];

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'products_categories', fn () => ProductCategory::select('id', 'name')->get()
        );
    }
}
