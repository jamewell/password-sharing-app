<?php

namespace App\Livewire;

use App\Enums\ExpirationTime;
use App\Enums\MaxUses;
use App\Models\PasswordShare;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class PasswordShareForm extends Component
{
    public string $password = '';

    public int $max_uses = MaxUses::ONE->value;

    public int $expiration_time = ExpirationTime::THIRTY_MINUTES->value;

    public ?string $generatedUrl = null;

    /**
     * @return array<string, list<Enum|string>|string>
     */
    protected function rules(): array
    {
        return [
            'password' => 'required|string|min:1',
            'max_uses' => [
                'required',
                'integer',
                new Enum(MaxUses::class),
            ],
            'expiration_time' => [
                'required',
                'integer',
                new Enum(ExpirationTime::class),
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.password-share-form')->with([
            'maxUsesOptions' => MaxUses::cases(),
            'expirationTimeOptions' => ExpirationTime::label(),
        ]);
    }

    public function generateLink(): void
    {
        $this->validate();

        $passwordShare = new PasswordShare;
        $passwordShare->password = encrypt($this->password);
        $passwordShare->max_uses = $this->max_uses;
        $passwordShare->remaining_uses = $this->max_uses;
        $passwordShare->expires_at = now()->addMinutes($this->expiration_time);

        $passwordShare->save();

        $this->reset(['password']);
    }
}
