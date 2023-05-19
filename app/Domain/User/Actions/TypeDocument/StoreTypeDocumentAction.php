<?php

namespace App\Domain\User\Actions\TypeDocument;

use App\Domain\User\Dtos\TypeDocument\StoreTypeDocumentData;
use App\Domain\User\Models\TypeDocument;
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
