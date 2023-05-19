<?php

namespace App\Domain\Product\Models;

use App\Domain\Product\QueryBuilders\ProductQueryBuilder;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @method static Product join(...$parameters)
 * @method static Product orderBy(...$parameters)
 * @method static Product orderByDesc(...$parameters)
 * @method static Product orderBy(...$parameters)
 * @method static Product select(...$parameters)
 * @method static Product whereBetween(...$parameters)
 * @method static ProductFactory factory(...$parameters)
 * @method static ProductQueryBuilder query()
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

    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }
}
