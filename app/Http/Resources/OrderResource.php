<?php

namespace App\Http\Resources;

use App\Domain\Order\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order;
 */
class OrderResource extends JsonResource
{
    /**
     * @return array<mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'purchase_date' => $this->purchase_date,
            'payment_date' => $this->payment_date,
            'payment_status' => $this->payment_status,
            'purchase_total' => $this->purchase_total,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
