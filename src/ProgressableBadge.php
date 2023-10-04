<?php

namespace Maize\Badges;

use Illuminate\Database\Eloquent\Model;

abstract class ProgressableBadge extends Badge
{
    abstract public static function getTotal(): int;

    abstract public static function getCurrent(Model $model): int;

    public static function isAwarded(Model $model): bool
    {
        return static::getCurrent($model) >= static::getTotal();
    }
}
