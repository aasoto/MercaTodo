<?php

namespace App\Services\User;

use App\Dtos\User\TypeDocument\StoreTypeDocumentData;
use App\Models\TypeDocument;
use Illuminate\Support\Facades\Cache;

class TypesDocumentsServices
{
    /**
     * @param StoreTypeDocumentData $data
     */
    public function store(StoreTypeDocumentData $data): void
    {
        TypeDocument::create([
            'code' => $data->code,
            'name' => $data->name,
        ]);

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
