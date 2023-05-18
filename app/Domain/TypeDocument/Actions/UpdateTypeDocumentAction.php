<?php

namespace App\Domain\TypeDocument\Actions;

use App\Domain\TypeDocument\Dtos\UpdateTypeDocumentData;
use App\Domain\TypeDocument\Models\TypeDocument;
use Illuminate\Support\Facades\Cache;

class UpdateTypeDocumentAction
{
    /**
     * @param UpdateTypeDocumentData $data
     */
    public function handle(string $id, UpdateTypeDocumentData $data): void
    {
        TypeDocument::where('id', $id)->update([
            'name' => $data->name,
        ]);

        Cache::put('type_documents', TypeDocument::select('id', 'code', 'name')->get());
    }
}
