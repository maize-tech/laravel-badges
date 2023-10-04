<?php

namespace Maize\Badges\Actions;

use Maize\Badges\Support\Config;

class ClearBadgesAction
{
    public function __invoke(): int
    {
        return Config::getModel()->newQuery()->whereNotIn(
            column: 'badge',
            values: Config::getBadgeNames()
        )->delete();
    }
}
