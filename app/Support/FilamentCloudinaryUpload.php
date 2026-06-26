<?php

namespace App\Support;

use App\Services\CloudinaryService;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FilamentCloudinaryUpload
{
    public static function image(FileUpload $upload, string $folder): FileUpload
    {
        return static::configure($upload, $folder, 'image');
    }

    public static function video(FileUpload $upload, string $folder): FileUpload
    {
        return static::configure($upload, $folder, 'video');
    }

    public static function file(FileUpload $upload, string $folder): FileUpload
    {
        return static::configure($upload, $folder, 'raw');
    }

    protected static function configure(FileUpload $upload, string $folder, string $resourceType): FileUpload
    {
        return $upload
            ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, CloudinaryService $cloudinary) use ($folder, $resourceType): string {
                $result = match ($resourceType) {
                    'video' => $cloudinary->uploadVideo($file, $folder),
                    'raw' => $cloudinary->uploadFile($file, $folder),
                    default => $cloudinary->uploadImage($file, $folder),
                };

                CloudinaryUploadRegistry::remember($result);

                return $result['secure_url'];
            })
            ->getUploadedFileUsing(fn (string $file): array => [
                'name' => basename(parse_url($file, PHP_URL_PATH) ?: $file),
                'size' => 0,
                'type' => null,
                'url' => Media::url(null, $file),
            ]);
    }
}
