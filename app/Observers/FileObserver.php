<?php

namespace App\Observers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    public function deleting(File $file): void
    {
        if (
            $file->file_path &&
            Storage::disk($file->disk)->exists($file->file_path)
        ) {
            Storage::disk($file->disk)->delete($file->file_path);
        }
    }
}
