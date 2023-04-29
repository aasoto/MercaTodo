<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
/**
 *
 */
trait StorageFiles
{
    public function upload(UploadedFile $file, string $path, int $counter): string
    {
        $filename = time().$counter.'.'.$file->extension();
        $file->storeAs($path, $filename, 'public');

        return $filename;
    }

    public function remove(string $path, string $file): void
    {
        // unlink(storage_path($path.$file));
        Storage::disk('public')->delete($path.'/'.$file);
    }
}
