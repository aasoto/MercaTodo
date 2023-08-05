<?php

namespace App\Domain\Product\Actions\Unit;

use App\Domain\Product\Dtos\Unit\UpdateUnitData;
use App\Domain\Product\Models\Unit;
use Illuminate\Support\Facades\Cache;

class UpdateUnitAction
{
    /**
     * @param UpdateUnitData $data
     */
    public function handle(string $id, UpdateUnitData $data): int
    {
        $response = Unit::where('id', $id)->update([
            'name' => $data->name,
        ]);

        Cache::put('units', Unit::select('id', 'code', 'name')->get());

        return $response;
    }
}
