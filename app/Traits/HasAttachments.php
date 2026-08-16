<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasAttachments
{
    public static function bootHasAttachments(): void
    {
        /** @var class-string<Model> $class */
        $class = static::class;

        foreach (static::$attachOne ?? [] as $field) {
            $class::resolveRelationUsing($field, function ($model) use ($field) {
                return $model->morphOne(File::class, 'attachment')
                    ->where('field', $field);
            });
        }

        foreach (static::$attachMany ?? [] as $field) {
            $class::resolveRelationUsing($field, function ($model) use ($field) {
                return $model->morphMany(File::class, 'attachment')
                    ->where('field', $field);
            });
        }
    }
}
