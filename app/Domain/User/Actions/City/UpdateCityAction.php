<?php

namespace App\Domain\User\Actions\City;

use App\Domain\User\Dtos\City\UpdateCityData;
use App\Domain\User\Models\City;
use Illuminate\Support\Facades\Cache;

class UpdateCityAction
{
    public function handle(string $id, UpdateCityData $data): void
    {
        City::where('id', $id)->update([
            'name'      => $data->name,
            'state_id'  => $data->state_id,
        ]);

        Cache::put('cities',
            City::select('id', 'name', 'state_id')->orderBy('name')->get()
        );
    }
}
