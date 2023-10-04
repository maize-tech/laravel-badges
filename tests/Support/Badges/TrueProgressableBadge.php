<?php

namespace Maize\Badges\Tests\Support\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\ProgressableBadge;

class TrueProgressableBadge extends ProgressableBadge
{
    public static function getTotal(): int
    {
        return 10;
    }

    public static function getCurrent(Model $model): int
    {
        return 20;
    }
}
