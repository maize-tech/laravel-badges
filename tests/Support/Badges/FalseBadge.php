<?php

namespace Maize\Badges\Tests\Support\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\Badge;

class FalseBadge extends Badge
{
    public static function isAwarded(Model $model): bool
    {
        return false;
    }
}
