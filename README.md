<p align="center">
<picture>
  <source media="(prefers-color-scheme: dark)" srcset="/art/socialcard-dark.png">
  <source media="(prefers-color-scheme: light)" srcset="/art/socialcard-light.png">
  <img src="/art/socialcard-light.png" alt="Social Card of Laravel Badges">
</picture>
</p>

# Laravel Badges

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maize-tech/laravel-badges.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-badges)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/maize-tech/laravel-badges/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/maize-tech/laravel-badges/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/maize-tech/laravel-badges/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/maize-tech/laravel-badges/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/maize-tech/laravel-badges.svg?style=flat-square)](https://packagist.org/packages/maize-tech/laravel-badges)

This package lets you easily add badge mechanics to your application.

## Installation

You can install the package via composer:

```bash
composer require maize-tech/laravel-badges
```

You can publish the config and migration files and run the migrations with:

```bash
php artisan badges:install
```

This is the contents of the published config file:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Badge model
    |--------------------------------------------------------------------------
    |
    | Here you may specify the fully qualified class name of the badge model.
    |
    */

    'model' => Maize\Badges\Models\BadgeModel::class,

    /*
    |--------------------------------------------------------------------------
    | Badges
    |--------------------------------------------------------------------------
    |
    | Here you may specify the list of fully qualified class name of badges.
    |
    */

    'badges' => [
        // App\Badges\FirstLogin::class,
    ],

];
```

## Usage

### Basic

To use the package, firstly you should implement the `Maize\Badges\HasBadges` interface and apply the `Maize\Badges\InteractsWithBadges` trait to all models who can have badges:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Maize\Badges\HasBadges;
use Maize\Badges\InteractsWithBadges;

class User extends Authenticatable implements HasBadges
{
    use InteractsWithBadges;
}
```

Once done, all you have to do is define a class for each badge, extend the `Maize\Badges\Badge` class and implement the `isAwarded` abstract method:

```php
<?php

namespace App\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\Badge;

class FirstLogin extends Badge
{
    public static function isAwarded(Model $model): bool
    {
        return $model->logins()->exists();
    }
}
```

Once done, don't forget to list the newly created badge within the `badges` list under `config/badges.php`:

```php
'badges' => [
    App\Badges\FirstLogin::class,
],
```

### Progressable badges

If your badge can have progress, you should extend the `Maize\Badges\ProgressableBadge` class and implement both the `getTotal` and `getCurrent` abstract methods:

```php
<?php

namespace App\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\ProgressableBadge;

class FiveLogins extends ProgressableBadge
{
    public static function getTotal(): int
    {
        return 5;
    }

    public static function getCurrent(Model $model): bool
    {
        return $model->logins()->count();
    }
}
```

Under the hoods, the `isAwarded` method checks if `getCurrent` is equals or greater than `getTotal`.

### Badge metadata

What is a badge without a name or description?

To accomplish this, you can override the `metadata` method within all badge classes.

Here is an example implementation using Laravel built-in translation method and the badge slug:

```php
<?php

namespace App\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\Badge;

class FirstLogin extends Badge
{
    public static function isAwarded(Model $model): bool
    {
        return $model->logins()->exists();
    }
    
    public static function metadata(): array
    {
        $slug = static::slug();

        return [
            'name' => __("badges.{$slug}.name"),
            'description' => __("badges.{$slug}.name"),
        ];    
    }
}
```

Once done, you can retrieve the metadata using both the badge class and the `BadgeModel` entities:

```php
use App\Badges\FirstLogin;
use Maize\Badges\Models\BadgeModel;

$user->giveBadge(FirstLogin::class);

$user->badges()->first()->metadata; // returns the list of metadata attributes

$user->badges()->first()->getMetadata('description'); // returns the attribute with key 'description'

FirstLogin::metadata(); // returns the list of metadata attributes

FirstLogin::getMetadata('description'); // returns the metadata attribute with key 'description'
```

### Custom badge slug

All badges have a default slug used when storing a badge awarded event into the database.

The default slug is the badge's fully qualified class name.
For example, `FirstLogin` badge's slug would be `App\Badges\FirstLogin`.

You can however customize the default behaviour overriding the `slug` method.

Here is an example using the badge's class basename in kebab case:

```php
<?php

namespace App\Badges;

use Illuminate\Database\Eloquent\Model;
use Maize\Badges\Badge;

class FirstLogin extends Badge
{
    public static function slug(): string
    {
        return str(static::class)
            ->classBasename()
            ->kebab()
            ->toString(); // returns 'first-login'
    }

    public static function isAwarded(Model $model): bool
    {
        return $model->logins()->exists();
    }
}
```

Beware that all badge classes should have a unique slug to prevent inconsistencies.

### Giving a badge

You can give a badge to any entity implementing the `HasBadges` interface using one of the following methods:

```php
use App\Badges\FirstLogin;

$user->giveBadge(FirstLogin::class);

FirstLogin::giveTo($user);
```

When giving a badge, the `isAwarded` method will be evaluated to make sure the entity meets the conditions.

Every time a badge is given, a `BadgeAwarded` event will also be fired.

### Check if a badge is awarded

To check if an entity has a badge, you can use the `hasBadge` method:

```php
use App\Badges\FirstLogin;

$user->hasBadge(FirstLogin::class)
```

### Retrieve awarded badges

To retrieve all badges awarded by an entity, you can use the `badges` relationship which returns a list of `BadgeModel` entities.

```php
use Maize\Badges\Models\BadgeModel;

$user->badges->map->badge; // returns the list of awarded badges slug
```

### Sync badges

To sync all badges for a given entity, you can use the `syncBadges` method, which retrieves all badges within the `badges` list under `config/badges.php` and checks whether it can be awarded or not.

```php
$user->syncBadges();
```

### Scheduling badges cleanup

The package also comes with the `badges:clear` command, which automatically deletes all stored badges which are not anymore listed within the `badges` list under `config/badges.php`:

You may schedule the command using the `schedule` method of the console kernel (usually located under the `App\Console` directory):

```php
use Maize\Badges\Commands\ClearBadgesCommand;

$schedule->command(ClearBadgesCommand::class)->daily();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/maize-tech/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](https://github.com/maize-tech/.github/security/policy) on how to report security vulnerabilities.

## Credits

- [Riccardo Dalla Via](https://github.com/riccardodallavia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
