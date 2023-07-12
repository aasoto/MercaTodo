<?php

namespace App\Domain\Product\Services\Contracts;

use App\Domain\Product\Models\Product;

interface FromQuery
{
    /**
     * @return Product
     */
    public function query();
}
