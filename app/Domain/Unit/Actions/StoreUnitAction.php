<?php

namespace App\Domain\Unit\Actions;

use App\Domain\Unit\Dtos\StoreUnitData;
use App\Domain\Unit\Models\Unit;
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
