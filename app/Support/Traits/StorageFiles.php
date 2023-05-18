<?php

namespace App\Support\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait StorageFiles
{
    public function upload(UploadedFile $file, string $path, int $counter): string
    {
        $filename = time().$counter.'.'.$file->extension();
        $file->storeAs($path, $filename, 'public');

        Log::channel('files')->info('Add file in path '.$path.' with the name '.$filename);

        return $filename;
    }

    public function remove(string $path, string $file): void
    {
        Storage::disk('public')->delete($path.'/'.$file);

        Log::channel('files')->warning('Delete file '.$path.'/'.$file);

    }
}
