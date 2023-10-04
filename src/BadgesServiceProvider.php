<?php

namespace Maize\Badges;

use Maize\Badges\Commands\ClearBadgesCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BadgesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-badges')
            ->hasConfigFile()
            ->hasMigration('create_badge_model_table')
            ->hasCommand(ClearBadgesCommand::class)
            ->hasInstallCommand(
                fn (InstallCommand $command) => $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('maize-tech/laravel-badges')
            );
    }
}
