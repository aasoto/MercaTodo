<?php

namespace App\Services\User;

use App\Dtos\User\TypeDocument\StoreTypeDocumentData;
use App\Dtos\User\TypeDocument\UpdateTypeDocumentData;
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
     * @param UpdateTypeDocumentData $data
     */
    public function update(string $id, UpdateTypeDocumentData $data): void
    {
        TypeDocument::where('id', $id)->update([
            'name' => $data->name,
        ]);

        Cache::put('type_documents', TypeDocument::select('id', 'code', 'name')->get());
    }
}
