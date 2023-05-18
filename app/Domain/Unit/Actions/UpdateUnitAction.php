<?php

namespace App\Domain\Unit\Actions;

use App\Domain\Unit\Dtos\UpdateUnitData;
use App\Domain\Unit\Models\Unit;
use Illuminate\Support\Facades\Cache;

class UpdateUnitAction
{
    /**
     * @param UpdateUnitData $data
     */
    public function handle(string $id, UpdateUnitData $data): void
    {
        Unit::where('id', $id)->update([
            'name' => $data->name,
        ]);

        Cache::put('units', Unit::select('id', 'code', 'name')->get());
    }
}
