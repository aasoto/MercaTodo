<?php

namespace App\Domain\Order\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilder extends Builder
{
    public function whereAuthUser(): self
    {
        $authenticated_user_id = auth()->user()->id;
        return $authenticated_user_id ? $this->where('user_id', $authenticated_user_id) : $this;
    }
}
