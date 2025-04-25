<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $password
 * @property int $max_uses
 * @property int $remaining_uses
 * @property Carbon $expires_at
 */
class PasswordShare extends Model
{
    /* @phpstan-ignore-next-line */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'id',
        'password',
        'max_uses',
        'remaining_uses',
        'expires_at',
    ];

    // @phpstan-ignore-next-line
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at->isPast() || $this->remaining_uses <= 0;
    }

    public function decrementRemainingUses(): void
    {
        $this->decrement('remaining_uses');
        $this->save();
    }
}
