<?php

namespace App\Domain\Order\QueryBuilders;

use App\Domain\Order\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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
 */
class OrderQueryBuilder extends Builder
{
    public function whereAuthUser(): self
    {
        $authenticated_user_id = auth()->user()?->id;
        return $authenticated_user_id ? $this->where('user_id', $authenticated_user_id) : $this;
    }
}
