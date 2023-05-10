<?php

namespace App\Services\Product;

use App\Dtos\Product\Unit\StoreUnitData;
use App\Dtos\Product\Unit\UpdateUnitData;
use App\Models\Unit;
use Illuminate\Support\Facades\Cache;

class UnitsServices
{
    /**
     * @param StoreUnitData $data
     */
    public function store(StoreUnitData $data): void
    {
        Unit::create([
            'code' => $data->code,
            'name' => $data->name,
        ]);

        Cache::put('units', Unit::select('id', 'code', 'name')->get());
    }

    public function edit(string $id): Unit|null
    {
        return Unit::where('id', $id)->first();
    }

    /**
     * @param UpdateUnitData $data
     */
    public function update(string $id, UpdateUnitData $data): void
    {
        Unit::where('id', $id)->update([
            'name' => $data->name,
        ]);

        Cache::put('units', Unit::select('id', 'code', 'name')->get());
    }
}
