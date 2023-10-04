<?php

namespace Maize\Badges\Tests\Support\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Maize\Badges\HasBadges;
use Maize\Badges\InteractsWithBadges;
use Maize\Badges\Tests\Support\Factories\UserFactory;

class User extends Model implements HasBadges
{
    use HasFactory;
    use InteractsWithBadges;

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }
}
