<?php

namespace App\Support;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CloudinaryModelMedia
{
    public static function sync(Model $model, string $sourceAttribute, string $urlAttribute, string $publicIdAttribute, string $resourceType = 'image'): void
    {
        if (! $model->isDirty($sourceAttribute)) {
            return;
        }

        $value = $model->getAttribute($sourceAttribute);
        $oldPublicId = $model->getOriginal($publicIdAttribute);
        $upload = CloudinaryUploadRegistry::get($value);

        if (blank($value)) {
            $model->setAttribute($urlAttribute, null);
            $model->setAttribute($publicIdAttribute, null);
            static::deleteQuietly($oldPublicId, $resourceType);

            return;
        }

        if (Media::isUrl($value)) {
            $model->setAttribute($urlAttribute, $value);

            if ($upload) {
                $model->setAttribute($publicIdAttribute, $upload['public_id']);

                if ($oldPublicId && $oldPublicId !== $upload['public_id']) {
                    static::deleteQuietly($oldPublicId, $resourceType);
                }
            }
        }
    }

    protected static function deleteQuietly(?string $publicId, string $resourceType): void
    {
        if (! $publicId) {
            return;
        }

        try {
            app(CloudinaryService::class)->delete($publicId, $resourceType);
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
