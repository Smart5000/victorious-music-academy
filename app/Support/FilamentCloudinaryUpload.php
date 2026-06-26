<?php

namespace App\Support;

use App\Services\CloudinaryService;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

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
                Log::info('Filament Cloudinary upload started.', [
                    'folder' => $folder,
                    'resource_type' => $resourceType,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);

                try {
                    $result = match ($resourceType) {
                        'video' => $cloudinary->uploadVideo($file, $folder),
                        'raw' => $cloudinary->uploadFile($file, $folder),
                        default => $cloudinary->uploadImage($file, $folder),
                    };

                    CloudinaryUploadRegistry::remember($result);

                    Log::info('Filament Cloudinary upload completed.', [
                        'folder' => $folder,
                        'resource_type' => $resourceType,
                        'public_id' => $result['public_id'],
                    ]);

                    return $result['secure_url'];
                } catch (Throwable $exception) {
                    report($exception);

                    Log::error('Filament Cloudinary upload failed; falling back to public disk.', [
                        'folder' => $folder,
                        'resource_type' => $resourceType,
                        'exception' => $exception::class,
                        'message' => $exception->getMessage(),
                    ]);

                    $extension = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'upload';
                    $path = trim($folder, '/').'/'.Str::uuid().'.'.$extension;

                    Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));

                    Log::warning('Filament upload saved to public disk fallback.', [
                        'path' => $path,
                    ]);

                    return $path;
                }
            })
            ->getUploadedFileUsing(fn (string $file): array => [
                'name' => basename(parse_url($file, PHP_URL_PATH) ?: $file),
                'size' => 0,
                'type' => null,
                'url' => Media::url(null, $file),
            ]);
    }
}
