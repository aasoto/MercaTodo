<?php

namespace App\Domain\Product\Actions\Unit;

use App\Domain\Product\Dtos\Unit\StoreUnitData;
use App\Domain\Product\Models\Unit;
use Illuminate\Support\Facades\Cache;

class StoreUnitAction
{
    /**
     * @param StoreUnitData $data
     */
    public function handle(StoreUnitData $data): void
    {
        Unit::create([
            'code' => $data->code,
            'name' => $data->name,
        ]);

        Cache::put('units', Unit::select('id', 'code', 'name')->get());
    }
}
