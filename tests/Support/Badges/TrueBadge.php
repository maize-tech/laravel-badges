<?php

namespace Maize\Badges\Tests\Support\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\Badge;

class TrueBadge extends Badge
{
    public static function isAwarded(Model $model): bool
    {
        return true;
    }

    public static function metadata(): array
    {
        return [
            'name' => 'True Badge',
        ];
    }
}
