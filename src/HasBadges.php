<?php

namespace Maize\Badges;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Maize\Badges\Models\BadgeModel;

/**
 * @property \Illuminate\Database\Eloquent\Collection $badges
 */
interface HasBadges
{
    public function badges(): MorphMany;

    public function giveBadge(string $badge): ?BadgeModel;

    public function hasBadge(string $badge): bool;

    public function syncBadges(): Collection;
}
