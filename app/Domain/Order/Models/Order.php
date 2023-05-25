<?php

namespace App\Domain\Order\Models;

use App\Domain\Order\QueryBuilders\OrderQueryBuilder;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @method static OrderQueryBuilder query()
 */
class Order extends Model
{
    use HasFactory;

    /**
     * @param Builder $query
     * @return OrderQueryBuilder
     */
    public function newEloquentBuilder($query): OrderQueryBuilder
    {
        return new OrderQueryBuilder($query);
    }

    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }
}
