<?php

namespace App\Classes\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class Action
{
    public function create(array $data): void
    {
        Product::create($data);
    }

    public function show(string $slug): Product
    {
        return Product::where('slug', $slug)->first();
    }

    public function update(int $id, array $data): void
    {
        Product::where('id', $id)->update($data);
    }

    public function delete(string $slug): void
    {
        Product::where('slug', $slug)->delete();
    }
}
