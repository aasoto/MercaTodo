<?php

namespace App\Domain\Product\Models;

use App\Domain\Order\Models\OrderHasProduct;
use App\Domain\Product\QueryBuilders\ProductQueryBuilder;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

/**
 * @property int id
 * @property string name
 * @property string slug
 * @property int products_category_id
 * @property string barcode
 * @property string description
 * @property double price
 * @property int stock
 * @property string unit
 * @property string picture_1
 * @property string picture_2
 * @property string picture_3
 * @property string availability
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

    public function orders(): HasMany
    {
        return $this->hasMany(OrderHasProduct::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'products_category_id');
    }

    public function product_unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'code', 'unit');
    }
}
