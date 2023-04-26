<?php
namespace App\Traits;

use Illuminate\Http\UploadedFile;

/**
 *
 */
trait StorageFiles
{
    public function upload(UploadedFile $file, string $path, int $counter): string
    {
        $filename = time().$counter.'.'.$file->extension();
        $file->move(public_path($path), $filename);
        // $request->picture_1->storeAs('images/products', $filename, 'public');

        return $filename;
    }

    public function remove(string $path, string $file): void
    {
        unlink(public_path($path.$file));
    }
}
