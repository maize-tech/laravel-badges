<?php

namespace Maize\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\Models\BadgeModel;

abstract class Badge
{
    abstract public static function isAwarded(Model $model): bool;

    public static function slug(): string
    {
        return static::class;
    }

    public static function metadata(): array
    {
        return [];
    }

    public static function getMetadata(string $key): mixed
    {
        return data_get(static::metadata(), $key);
    }

    public static function giveTo(HasBadges $model): ?BadgeModel
    {
        return $model->giveBadge(static::class);
    }
}
