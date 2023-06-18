<?php

namespace App\Domain\Order\Models;

use App\Domain\Order\QueryBuilders\OrderHasProductQueryBuilder;
use App\Domain\Product\Models\Product;
use Database\Factories\OrderHasProductFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @method static OrderHasProduct join(...$parameters)
 * @method static OrderHasProduct select(...$parameters)
 * @method static OrderHasProductQueryBuilder query()
 */
class OrderHasProduct extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    /**
     * @param Builder $query
     * @return OrderHasProductQueryBuilder
     */
    public function newEloquentBuilder($query): OrderHasProductQueryBuilder
    {
        return new OrderHasProductQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return OrderHasProductFactory::new();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
