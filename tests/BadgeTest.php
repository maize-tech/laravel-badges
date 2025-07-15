<?php

use Illuminate\Support\Facades\Artisan;
use Maize\Badges\Commands\ClearBadgesCommand;
use Maize\Badges\Events\BadgeAwarded;
use Maize\Badges\HasBadges;
use Maize\Badges\Models\BadgeModel;
use Maize\Badges\Tests\Support\Badges\FalseBadge;
use Maize\Badges\Tests\Support\Badges\FalseProgressableBadge;
use Maize\Badges\Tests\Support\Badges\TrueBadge;
use Maize\Badges\Tests\Support\Badges\TrueProgressableBadge;
use Maize\Badges\Tests\Support\Models\Team;
use Maize\Badges\Tests\Support\Models\User;

it('should give badges', function (Closure $modelFactory) {
    $model = $modelFactory();
    expect($model->giveBadge(TrueBadge::class))
        ->not()->toBeNull()
        ->and($model->giveBadge(FalseBadge::class))
        ->toBeNull()
        ->and(TrueBadge::giveTo($model))
        ->not()->toBeNull()
        ->and(FalseBadge::giveTo($model))
        ->toBeNull();
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);


it('should give progressable badges', function (HasBadges $model) {
    expect($model->giveBadge(TrueProgressableBadge::class))
        ->not()->toBeNull()
        ->and($model->giveBadge(FalseProgressableBadge::class))
        ->toBeNull()
        ->and(TrueProgressableBadge::giveTo($model))
        ->not()->toBeNull()
        ->and(FalseProgressableBadge::giveTo($model))
        ->toBeNull();
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);


it('should check if model has badge', function (HasBadges $model) {
    $model->giveBadge(TrueBadge::class);

    expect(
        $model->hasBadge(TrueBadge::class)
    )->toBeTrue();
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);


it('should retrieve badge metadata', function (HasBadges $model) {
    $badge = $model->giveBadge(TrueBadge::class);

    expect($badge->metadata)
        ->toMatchArray(['name' => 'True Badge'])
        ->and(TrueBadge::metadata())
        ->toMatchArray(['name' => 'True Badge'])
        ->and($badge->getMetadata('name'))
        ->toBe('True Badge')
        ->and(TrueBadge::getMetadata('name'))
        ->toBe('True Badge');
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);


it('should retrieve all awarded badges', function (HasBadges $model) {
    $model->giveBadge(TrueBadge::class);
    $model->giveBadge(TrueProgressableBadge::class);

    expect($model->badges)
        ->toHaveCount(2)
        ->toContainOnlyInstancesOf(BadgeModel::class);
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);


it('should sync badges', function (HasBadges $model) {
    expect($model->syncBadges())
        ->toHaveCount(2)
        ->toContainOnlyInstancesOf(BadgeModel::class);
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);


it('should delete badges on model delete', function (HasBadges $model) {
    $model->syncBadges();
    $model->delete();

    expect(BadgeModel::count())->toBe(0);
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);


it('should fire event on badge awarded', function (HasBadges $model) {
    \Illuminate\Support\Facades\Event::fake();

    $model->giveBadge(TrueBadge::class);

    \Illuminate\Support\Facades\Event::assertDispatched(
        event: BadgeAwarded::class,
        callback: function (BadgeAwarded $event) use ($model) {
            return $event->badgeModel->badge === TrueBadge::slug()
                && $event->badgeModel->model()->is($model);
        }
    );
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);

it('should clear old badges', function (HasBadges $model) {
    $model->badges()->create([
        'badge' => 'fake-badge',
    ]);

    Artisan::call(ClearBadgesCommand::class);

    expect($model->badges)->toBeEmpty();
})->with([
    [fn () => User::factory()->create()],
    [fn () => Team::factory()->create()],
]);
