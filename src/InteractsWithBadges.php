<?php

namespace Maize\Badges;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Maize\Badges\Exceptions\InvalidBadge;
use Maize\Badges\Models\BadgeModel;
use Maize\Badges\Support\Config;

trait InteractsWithBadges
{
    public static function bootInteractsWithBadges(): void
    {
        static::deleting(
            fn (self $model) => $model->badges()->delete()
        );
    }

    public function badges(): MorphMany
    {
        return $this->morphMany(
            related: Config::getModel(),
            name: 'model'
        );
    }

    public function giveBadge(string $badge): ?BadgeModel
    {
        if (! $badge = Config::getBadge($badge)) {
            throw InvalidBadge::make($badge);
        }

        if (! $badge->isAwarded($this)) {
            return null;
        }

        return $this->badges()->firstOrCreate([
            'badge' => $badge->slug(),
        ]);
    }

    public function hasBadge(string $badge): bool
    {
        $badge = Config::getBadge($badge);

        return $this
            ->badges()
            ->where('badge', $badge?->slug())
            ->exists();
    }

    public function syncBadges(): Collection
    {
        return collect(Config::getBadgeClasses())
            ->map(fn (string $badgeType) => $this->giveBadge($badgeType))
            ->filter();
    }
}
