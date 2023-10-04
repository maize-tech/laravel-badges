<?php

namespace Maize\Badges\Exceptions;

use InvalidArgumentException;

class InvalidBadge extends InvalidArgumentException
{
    public static function make($badge): self
    {
        return new self("Badge '{$badge}' does not exists.");
    }
}
