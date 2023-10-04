<?php

namespace Maize\Badges\Tests\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Maize\Badges\BadgesServiceProvider;
use Maize\Badges\Tests\Support\Badges\FalseBadge;
use Maize\Badges\Tests\Support\Badges\FalseProgressableBadge;
use Maize\Badges\Tests\Support\Badges\TrueBadge;
use Maize\Badges\Tests\Support\Badges\TrueProgressableBadge;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BadgesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        config()->set('badges.badges', [
            FalseBadge::class,
            FalseProgressableBadge::class,
            TrueBadge::class,
            TrueProgressableBadge::class,
        ]);

        $migration = include __DIR__.'/../../database/migrations/create_badge_model_table.php.stub';
        $migration->up();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
}
