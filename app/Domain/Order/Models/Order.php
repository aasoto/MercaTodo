<?php

namespace App\Domain\Order\Models;

use App\Domain\Order\QueryBuilders\OrderQueryBuilder;
use Carbon\Carbon;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @property string $id
 * @property string $code
 * @property string|null $request_id
 * @property string $user_id
 * @property Carbon $purchase_date
 * @property string $currency
 * @property string|null $url
 * @property string $payment_status
 * @property string $purchase_total
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static Order join(...$parameters)
 * @method static Order orderByDesc(...$parameters)
 * @method static Order pending()
 * @method static Order select(...$parameters)
 * @method static OrderQueryBuilder query()
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'purchase_date',
        'currency',
        'url',
        'payment_status',
        'purchase_total'
    ];

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

    public function canceled(): void
    {
        $this->update([
            'payment_status' => 'canceled'
        ]);
    }

    public function paid(): void
    {
        $this->update([
            'payment_status' => 'paid'
        ]);
    }

    public function pending(): void
    {
        $this->update([
            'payment_status' => 'pending'
        ]);
    }

    public function waiting(): void
    {
        $this->update([
            'payment_status' => 'waiting'
        ]);
    }
}
