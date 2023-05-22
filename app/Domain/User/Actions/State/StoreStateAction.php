<?php

namespace App\Domain\User\Actions\State;

use App\Domain\User\Dtos\State\StoreStateData;
use App\Domain\User\Models\State;
use Illuminate\Support\Facades\Cache;

class StoreStateAction
{
    public function handle(StoreStateData $data): void
    {
        State::create([
            'name' => $data->name,
        ]);

        Cache::put('states',
            State::select('id', 'name')->orderBy('name')->get()
        );
    }
}
