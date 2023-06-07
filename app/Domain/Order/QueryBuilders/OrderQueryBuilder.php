<?php

namespace App\Domain\Order\QueryBuilders;

use App\Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Order join(...$parameters)
 * @method static Order orderByDesc(...$parameters)
 * @method static Order pending()
 * @method static Order select(...$parameters)
 */
class OrderQueryBuilder extends Builder
{
    public function whereAuthUser(): self
    {
        $authenticated_user_id = auth()->user()->id;
        return $authenticated_user_id ? $this->where('user_id', $authenticated_user_id) : $this;
    }
}
