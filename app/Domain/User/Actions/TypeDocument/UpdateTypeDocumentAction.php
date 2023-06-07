<?php

namespace App\Domain\User\Actions\TypeDocument;

use App\Domain\User\Dtos\TypeDocument\UpdateTypeDocumentData;
use App\Domain\User\Models\TypeDocument;
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
