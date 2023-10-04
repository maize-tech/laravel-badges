<?php

namespace Maize\Badges\Support;

use Maize\Badges\Badge;
use Maize\Badges\Models\BadgeModel;

class Config
{
    public static function getModel(): BadgeModel
    {
        $model = config('badges.model') ?? BadgeModel::class;

        return new $model;
    }

    public static function getBadgeClasses(): array
    {
        return config('badges.badges') ?? [];
    }

    public static function getBadges(): array
    {
        return collect(
            static::getBadgeClasses()
        )->keyBy(
            fn ($badgeType) => $badgeType::slug()
        )->toArray();
    }

    public static function getBadgeNames(): array
    {
        return collect(
            static::getBadges()
        )->keys()->toArray();
    }

    public static function getBadge(string $badge): ?Badge
    {
        if (class_exists($badge) && in_array($badge, static::getBadgeClasses())) {
            return new $badge;
        }

        if ($badgeClass = data_get(static::getBadges(), $badge)) {
            return new $badgeClass;
        }

        return null;
    }
}
