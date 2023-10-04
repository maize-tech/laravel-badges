<?php

namespace Maize\Badges\Events;

use Maize\Badges\Models\BadgeModel;

class BadgeAwarded
{
    public function __construct(
        public BadgeModel $badgeModel
    ) {
        //
    }
}
