<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use RuntimeException;

class CloudinaryService
{
    public function uploadImage(UploadedFile|string $file, string $folder): array
    {
        return $this->upload($file, $folder, 'image');
    }

    public function uploadVideo(UploadedFile|string $file, string $folder): array
    {
        return $this->upload($file, $folder, 'video');
    }

    public function uploadFile(UploadedFile|string $file, string $folder): array
    {
        return $this->upload($file, $folder, 'raw');
    }

    public function delete(?string $publicId, string $resourceType = 'image'): void
    {
        if (blank($publicId)) {
            return;
        }

        $this->cloudinary()
            ->uploadApi()
            ->destroy($publicId, [
                'resource_type' => $resourceType,
                'invalidate' => true,
            ]);
    }

    public function replace(?string $oldPublicId, UploadedFile|string $file, string $folder, string $resourceType = 'image'): array
    {
        $upload = $this->upload($file, $folder, $resourceType);

        if ($oldPublicId && $oldPublicId !== $upload['public_id']) {
            $this->delete($oldPublicId, $resourceType);
        }

        return $upload;
    }

    private function upload(UploadedFile|string $file, string $folder, string $resourceType): array
    {
        $path = $file instanceof UploadedFile ? $file->getRealPath() : $file;

        if (! $path) {
            throw new RuntimeException('Unable to read the uploaded file before sending it to Cloudinary.');
        }

        $response = $this->cloudinary()
            ->uploadApi()
            ->upload($path, [
                'folder' => trim($folder, '/'),
                'resource_type' => $resourceType,
                'overwrite' => true,
                'unique_filename' => true,
            ]);

        $secureUrl = Arr::get($response, 'secure_url');
        $publicId = Arr::get($response, 'public_id');

        if (! $secureUrl || ! $publicId) {
            throw new RuntimeException('Cloudinary did not return a secure URL for the uploaded file.');
        }

        return [
            'secure_url' => $secureUrl,
            'public_id' => $publicId,
            'resource_type' => $resourceType,
        ];
    }

    private function cloudinary(): Cloudinary
    {
        $url = (string) config('services.cloudinary.url');

        if ($url !== '') {
            return new Cloudinary($url);
        }

        $cloudName = (string) config('services.cloudinary.cloud_name');
        $apiKey = (string) config('services.cloudinary.api_key');
        $apiSecret = (string) config('services.cloudinary.api_secret');

        if ($cloudName === '' || $apiKey === '' || $apiSecret === '') {
            throw new RuntimeException('Cloudinary is not configured. Add Cloudinary credentials to the environment.');
        }

        return new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
        ]);
    }
}
