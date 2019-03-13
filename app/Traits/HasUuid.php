<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait HasUuid
{
    public static function bootHasUuid()
    {
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }
}
