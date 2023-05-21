<?php

namespace App\Domain\User\Actions\City;

use App\Domain\User\Dtos\City\StoreCityData;
use App\Domain\User\Models\City;
use Illuminate\Support\Facades\Cache;

class StoreCityAction
{
    public function handle(StoreCityData $data): void
    {
        City::create([
            'name' => $data->name,
            'state_id' => $data->state_id,
        ]);

        Cache::put('cities',
            City::select('id', 'name', 'state_id')->orderBy('name')->get()
        );
    }
}
