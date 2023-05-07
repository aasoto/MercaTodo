<?php

namespace App\Services\Product;

use App\Models\Unit;
use Illuminate\Support\Facades\Cache;

class UnitsServices
{
    /**
     * @param array<string> $data
     */
    public function create(array $data): void
    {
        Unit::create($data);
        Cache::put('units', Unit::select('id', 'code', 'name')->get());
    }

    public function edit(string $id): Unit|null
    {
        return Unit::where('id', $id)->first();
    }

    /**
     * @param array<mixed> $data
     */
    public function update(string $id, array $data): void
    {
        Unit::where('id', $id)->update($data);
        Cache::put('units', Unit::select('id', 'code', 'name')->get());
    }
}
