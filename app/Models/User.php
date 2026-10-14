<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN  = 'admin';
    const ROLE_MEMBRE = 'membre';

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'password'                => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // ─── 2FA ─────────────────────────────────────────────

    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret)
            && !is_null($this->two_factor_confirmed_at);
    }

    public function getTwoFactorSecret(): ?string
    {
        return $this->two_factor_secret
            ? Crypt::decryptString($this->two_factor_secret)
            : null;
    }

    public function setTwoFactorSecret(string $secret): void
    {
        $this->two_factor_secret = Crypt::encryptString($secret);
    }

    public function getTwoFactorRecoveryCodes(): array
    {
        if (!$this->two_factor_recovery_codes) {
            return [];
        }

        return json_decode(Crypt::decryptString($this->two_factor_recovery_codes), true) ?? [];
    }

    public function setTwoFactorRecoveryCodes(array $codes): void
    {
        $this->two_factor_recovery_codes = Crypt::encryptString(json_encode($codes));
    }
}