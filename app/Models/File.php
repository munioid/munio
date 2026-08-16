<?php

namespace App\Models;

use App\Observers\FileObserver;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[ObservedBy(FileObserver::class)]
#[Fillable([
    'field',
    'file_name',
    'file_path',
    'file_size',
    'content_type',
    'created_by',
])]
class File extends Model
{
    use HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    // ## Relationships ###
    public function attachment(): MorphTo
    {
        return $this->morphTo();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ## Functions ###
    public function getPath()
    {
        $path = Storage::url($this->file_path);

        return $path;
    }

    public static function upload(
        UploadedFile $file,
        string $field,
        ?string $directory = null,
        ?User $user = null,
    ): self {
        $tenant = Filament::getTenant();

        $disk ??= config('filesystems.default');
        $diskStorage = Storage::disk($disk);

        $directory = trim($directory, '/');
        $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
        $filepath = $tenant->code.($directory ? '/'.$directory : '');

        $path = $diskStorage->putFileAs($filepath, $file, $filename);

        return static::create([
            'field' => $field,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'content_type' => $file->getMimeType(),
            'created_by' => $user?->id,
        ]);
    }
}
