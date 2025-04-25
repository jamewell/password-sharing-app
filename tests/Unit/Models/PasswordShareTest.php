<?php

namespace Tests\Unit\Models;

use App\Models\PasswordShare;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_expired_returns_true_when_expired(): void
    {
        $passwordShare = PasswordShare::factory()->create([
            'expires_at' => now()->subHour(),
        ]);
        $this->assertTrue($passwordShare->isExpired());
    }

    public function test_is_expired_returns_true_when_no_uses_left(): void
    {
        $passwordShare = PasswordShare::factory()->create([
            'remaining_uses' => 0,
            'expires_at' => now()->addHour(),
        ]);
        $this->assertTrue($passwordShare->isExpired());
    }

    public function test_is_expired_returns_false_when_valid(): void
    {
        $passwordShare = PasswordShare::factory()->create([
            'remaining_uses' => 1,
            'expires_at' => now()->addHour(),
        ]);
        $this->assertFalse($passwordShare->isExpired());
    }

    public function test_decrement_remaining_uses(): void
    {
        $passwordShare = PasswordShare::factory()->create([
            'remaining_uses' => 2,
        ]);

        $passwordShare->decrementRemainingUses();
        $this->assertEquals(1, $passwordShare->fresh()->remaining_uses);

        $passwordShare->decrementRemainingUses();
        $this->assertEquals(0, $passwordShare->fresh()->remaining_uses);
    }
}
