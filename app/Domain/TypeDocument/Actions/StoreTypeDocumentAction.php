<?php

namespace App\Domain\TypeDocument\Actions;

use App\Domain\TypeDocument\Dtos\StoreTypeDocumentData;
use App\Domain\TypeDocument\Models\TypeDocument;
use Illuminate\Support\Facades\Cache;

class StoreTypeDocumentAction
{
    /**
     * @param StoreTypeDocumentData $data
     */
    public function handle(StoreTypeDocumentData $data): void
    {
        TypeDocument::create([
            'code' => $data->code,
            'name' => $data->name,
        ]);

        Cache::put('type_documents', TypeDocument::select('id', 'code', 'name')->get());
    }
}
