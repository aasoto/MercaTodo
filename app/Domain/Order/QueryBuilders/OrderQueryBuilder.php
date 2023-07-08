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

    public function whereDateBetween(?string $date_1, ?string $date_2): self|Order
    {
        if ($date_1 && $date_2) {
            return $this->whereBetween('orders.purchase_date', [$date_1, $date_2]);
        } elseif ($date_1) {
            return $this->where('orders.purchase_date', $date_1);
        } elseif ($date_2) {
            return $this->where('orders.purchase_date', $date_2);
        } else {
            return $this;
        }
    }

    public function whereUserNumberDocument(?string $number_document): self
    {
        // dd($number_document);
        return $number_document ?
            $this->where('users.number_document', $number_document) :
            $this;
    }
}
