<?php

namespace App\Console\Commands;

use App\Models\PasswordShare;
use Illuminate\Console\Command;

class CleanupExpiredPasswordShares extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shared-passwords:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup expired password shares';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Cleaning up expired password shares...');

        /* @phpstan-ignore-next-line */
        $deleted = PasswordShare::where('expires_at', '<=', now())
            ->orWhere('remaining_uses', '<=', 0)
            ->delete();

        $this->info("Deleted {$deleted} expired password shares.");
    }
}
