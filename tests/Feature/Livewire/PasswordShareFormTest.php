<?php

namespace Tests\Feature\Livewire;

use App\Enums\ExpirationTime;
use App\Enums\MaxUses;
use App\Livewire\PasswordShareForm;
use App\Models\PasswordShare;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\Rules\Enum;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordShareFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_renders_successfully(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->assertStatus(200);
    }

    public function test_displays_all_max_uses_options(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->assertSee(MaxUses::ONE->label())
            ->assertSee(MaxUses::TWO->label())
            ->assertSee(MaxUses::THREE->label())
            ->assertSee(MaxUses::FOUR->label())
            ->assertSee(MaxUses::FIVE->label());
    }

    public function test_displays_all_expiration_time_options(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->assertSee(ExpirationTime::THIRTY_MINUTES->text())
            ->assertSee(ExpirationTime::ONE_HOUR->text())
            ->assertSee(ExpirationTime::SIX_HOURS->text())
            ->assertSee(ExpirationTime::TWELVE_HOURS->text())
            ->assertSee(ExpirationTime::ONE_DAY->text());
    }

    public function test_requires_password(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->set('password', '')
            ->call('generateLink')
            ->assertHasErrors(['password' => 'required']);
    }

    public function test_validates_max_uses_range(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->set('max_uses', 0)
            ->call('generateLink')
            ->assertHasErrors(['max_uses' => Enum::class])
            ->set('max_uses', 6)
            ->call('generateLink')
            ->assertHasErrors(['max_uses' => Enum::class]);
    }

    public function test_validates_expiration_time_range(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->set('expiration_time', 0)
            ->call('generateLink')
            ->assertHasErrors(['expiration_time' => Enum::class])
            ->set('expiration_time', ExpirationTime::ONE_DAY->value + 1)
            ->call('generateLink')
            ->assertHasErrors(['expiration_time' => Enum::class]);
    }

    public function test_generates_password_share_link_with_valid_data(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->set('password', 'my-secret-password')
            ->set('max_uses', MaxUses::THREE->value)
            ->set('expiration_time', ExpirationTime::SIX_HOURS->value)
            ->call('generateLink')
            ->assertHasNoErrors()
            ->assertSet('generatedUrl', function ($url) {
                return str_contains($url, '/password/');
            });

        $this->assertDatabaseHas('password_shares', [
            'max_uses' => MaxUses::THREE->value,
            'remaining_uses' => MaxUses::THREE->value,
        ]);
    }

    public function test_resets_password_field_after_generation(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->set('password', 'my-secret-password')
            ->set('max_uses', MaxUses::ONE->value)
            ->set('expiration_time', ExpirationTime::ONE_HOUR->value)
            ->call('generateLink')
            ->assertSet('password', '');
    }

    public function test_shows_generated_url_after_success(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->set('password', 'my-secret-password')
            ->set('max_uses', MaxUses::ONE->value)
            ->set('expiration_time', ExpirationTime::ONE_HOUR->value)
            ->call('generateLink')
            ->assertSee('Your sharing link has been generated!')
            ->assertSeeHtml('wire:model="generatedUrl"');
    }

    public function test_encrypts_password_in_database(): void
    {
        $testPassword = 'my-secret-password';

        Livewire::test(PasswordShareForm::class)
            ->set('password', $testPassword)
            ->set('max_uses', MaxUses::ONE->value)
            ->set('expiration_time', ExpirationTime::ONE_HOUR->value)
            ->call('generateLink');

        $passwordShare = PasswordShare::first();
        $this->assertNotEquals($testPassword, $passwordShare->password);
        $this->assertEquals($testPassword, decrypt($passwordShare->password));
    }

    public function test_generated_url_is_signed_and_temporary(): void
    {
        Livewire::test(PasswordShareForm::class)
            ->set('password', 'test123')
            ->set('max_uses', MaxUses::ONE->value)
            ->set('expiration_time', ExpirationTime::ONE_HOUR->value)
            ->call('generateLink')
            ->assertSet('generatedUrl', function ($url) {
                // Verify URL has signature parameter
                $parsed = parse_url($url);
                parse_str($parsed['query'], $query);

                return isset($query['signature']) &&
                    isset($query['expires']);
            });
    }
}
