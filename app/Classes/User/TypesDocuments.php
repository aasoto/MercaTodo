<?php

namespace App\Classes\User;

use App\Models\TypeDocument;
use Illuminate\Support\Facades\Cache;

class TypesDocuments
{
    /**
     * @param array<string> $data
     */
    public function create(array $data): void
    {
        TypeDocument::create($data);
        Cache::put('type_documents', TypeDocument::select('id', 'code', 'name')->get());
    }

    public function edit(string $id): TypeDocument|null
    {
        return TypeDocument::where('id', $id)->first();
    }

    /**
     * @param array<mixed> $data
     */
    public function update(string $id, array $data): void
    {
        TypeDocument::where('id', $id)->update($data);
        Cache::put('type_documents', TypeDocument::select('id', 'code', 'name')->get());
    }
}
