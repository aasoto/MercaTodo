<?php

namespace App\Models;

use App\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @method static ProductQueryBuilder query()
 * @method static Product select(...$parameters)
 * @method static Product join(...$parameters)
 * @method static Product orderByDesc(...$parameters)
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
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

    /**
     * @param Builder $query
     * @return ProductQueryBuilder
     */
    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }
}
