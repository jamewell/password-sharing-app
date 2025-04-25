<?php

namespace Feature\Console\Commands;

use App\Models\PasswordShare;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CleanupExpiredPasswordSharesTest extends TestCase
{
    use RefreshDatabase;

    public function test_cleans_up_expired_password_shares(): void
    {
        $expired = PasswordShare::factory()->create([
            'expires_at' => now()->subHour(),
        ]);

        $valid = PasswordShare::factory()->create([
            'expires_at' => now()->addHour(),
        ]);

        Artisan::call('shared-passwords:cleanup');

        $this->assertDatabaseMissing('password_shares', ['id' => $expired->id]);
        $this->assertDatabaseHas('password_shares', ['id' => $valid->id]);
    }

    public function test_cleans_up_password_shares_with_no_remaining_uses(): void
    {
        $noUsesLeft = PasswordShare::factory()->create([
            'remaining_uses' => 0,
            'expires_at' => now()->addHour(),
        ]);

        $valid = PasswordShare::factory()->create([
            'remaining_uses' => 1,
            'expires_at' => now()->addHour(),
        ]);

        Artisan::call('shared-passwords:cleanup');

        $this->assertDatabaseMissing('password_shares', ['id' => $noUsesLeft->id]);
        $this->assertDatabaseHas('password_shares', ['id' => $valid->id]);
    }

    public function test_cleans_up_both_expired_and_no_remaining_uses(): void
    {
        $expired = PasswordShare::factory()->create([
            'expires_at' => now()->subHour(),
        ]);

        $noUsesLeft = PasswordShare::factory()->create([
            'remaining_uses' => 0,
            'expires_at' => now()->addHour(),
        ]);

        $valid = PasswordShare::factory()->create([
            'remaining_uses' => 1,
            'expires_at' => now()->addHour(),
        ]);

        Artisan::call('shared-passwords:cleanup');

        $this->assertDatabaseMissing('password_shares', ['id' => $expired->id]);
        $this->assertDatabaseMissing('password_shares', ['id' => $noUsesLeft->id]);
        $this->assertDatabaseHas('password_shares', ['id' => $valid->id]);
    }

    public function test_outputs_cleaned_up_count(): void
    {
        PasswordShare::factory()->count(3)->create([
            'expires_at' => now()->subHour(),
        ]);

        PasswordShare::factory()->count(2)->create([
            'remaining_uses' => 0,
            'expires_at' => now()->addHour(),
        ]);

        $command = Artisan::call('shared-passwords:cleanup');

        $output = Artisan::output();
        $this->assertStringContainsString('Deleted 5 expired password shares.', $output);
    }

    public function test_handles_no_expired_shares()
    {
        PasswordShare::factory()->count(2)->create([
            'remaining_uses' => 1,
            'expires_at' => now()->addHour(),
        ]);

        $command = Artisan::call('shared-passwords:cleanup');

        $output = Artisan::output();
        $this->assertStringContainsString('Deleted 0 expired password shares.', $output);
    }

    public function test_does_not_affect_valid_shares()
    {
        $validShares = PasswordShare::factory()->count(3)->create([
            'remaining_uses' => 1,
            'expires_at' => now()->addHour(),
        ]);

        Artisan::call('shared-passwords:cleanup');

        $validShares->each(function ($share) {
            $this->assertDatabaseHas('password_shares', ['id' => $share->id]);
        });
    }

    public function test_handles_large_number_of_expired_shares()
    {
        PasswordShare::factory()->count(1000)->create([
            'expires_at' => now()->subDay(),
        ]);

        Artisan::call('shared-passwords:cleanup');

        $this->assertEquals(0, PasswordShare::where('expires_at', '<', now())->count());
    }
}
