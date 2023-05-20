<?php

namespace App\Domain\User\Actions\State;

use App\Domain\User\Dtos\State\UpdateStateData;
use App\Domain\User\Models\State;
use Illuminate\Support\Facades\Cache;

class UpdateStateAction
{
    public function handle(string $id, UpdateStateData $data): void
    {
        State::where('id', $id)->update([
            'name' => $data->name,
        ]);

        Cache::put('states',
            State::select('id', 'name')->orderBy('name')->get()
        );
    }
}
