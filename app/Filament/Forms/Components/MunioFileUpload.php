<?php

namespace App\Filament\Forms\Components;

use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MunioFileUpload extends FileUpload
{
    protected string $relationship;

    protected array $uploadedFiles = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->relationship = $this->getName();

        $this->saveUploadedFileUsing(function (
            TemporaryUploadedFile $file
        ) {
            $tenant = Filament::getTenant();
            $contentType = $file->getMimeType();
            $size = $file->getSize();
            $disk = $this->getDiskName();
            $directory = $this->getDirectory().'/'.$tenant->code;
            $filename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $diskStorage = Storage::disk($disk);

            $path = $diskStorage->putFileAs($directory, $file, $filename);

            $user = Auth::user();
            $this->uploadedFiles[$path] = [
                'field' => $this->getName(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $size,
                'content_type' => $contentType,
                'created_by' => $user?->id,
            ];

            return $path;
        });

        $this->saveRelationshipsUsing(function ($record, $state) {
            foreach ((array) $state as $filepath) {
                $meta = $this->uploadedFiles[$filepath] ?? null;

                if (! $meta) {
                    continue;
                }

                $record->{$this->relationship}()->create($meta);
            }
        });

        $this->afterStateHydrated(function (FileUpload $component, $record) {
            if (! $record) {
                return;
            }

            $record->{$this->relationship}()->get()->each(function ($file) use (&$paths) {
                if (Storage::exists($file->file_path)) {
                    $paths[] = $file->file_path;
                } else {
                    $file->delete();
                }
            });

            $component->state($paths);
        });

        $this->deleteUploadedFileUsing(function (string|TemporaryUploadedFile $file, $record) {
            $path = $file;
            $disk = Storage::disk($this->getDiskName());

            $attachment = $record
                ->{$this->relationship}
                ->where('file_path', $path)
                ->first();

            if ($attachment) {
                if ($disk->exists($attachment->file_path)) {
                    $disk->delete($attachment->file_path);
                }

                $attachment->delete();
            }

            return true;
        });
    }

    public function relationship(string $relationship): static
    {
        $this->relationship = $relationship;

        return $this;
    }

    public function getRelationship(): string
    {
        return $this->relationship;
    }
}
