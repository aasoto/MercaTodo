<?php

namespace App\Classes\User;

use App\Models\TypeDocument;

class TypesDocuments
{
    /**
     * @param array<string> $data
     */
    public function create(array $data): TypeDocument
    {
        return TypeDocument::create($data);
    }

    public function edit(string $id): TypeDocument|null
    {
        return TypeDocument::where('id', $id)->first();
    }

    /**
     * @param array<mixed> $data
     */
    public function update(string $id, array $data): int
    {
        return TypeDocument::where('id', $id)->update($data);
    }
}
