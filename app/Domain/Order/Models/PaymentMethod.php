<?php

namespace App\Domain\Order\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PaymentMethod extends Model
{
    use HasFactory;

    public static function getFromCache(): Collection
    {
        return Cache::rememberForever(
            'payment_methods', fn () => PaymentMethod::select('id', 'code', 'name')->get()
        );
    }
}
