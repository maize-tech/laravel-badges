<?php

namespace Maize\Badges\Commands;

use Illuminate\Console\Command;
use Maize\Badges\Actions\ClearBadgesAction;

class ClearBadgesCommand extends Command
{
    public $signature = 'badges:clear';

    public $description = 'Clear all outdated badges';

    public function handle(): int
    {
        $deleted = app(ClearBadgesAction::class)();

        $this->info("Cleared {$deleted} badge(s)");

        return self::SUCCESS;
    }
}
