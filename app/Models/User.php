<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'username', 'email', 'password', 'role', 'pin_hash'])]
#[Hidden(['password', 'remember_token', 'pin_hash'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function hasPin(): bool
    {
        return !is_null($this->pin_hash);
    }

    public function verifyPin(string $pin): bool
    {
        if (!$this->hasPin()) return false;
        return password_verify($pin, $this->pin_hash);
    }

    public function setPin(string $pin): void
    {
        $this->pin_hash = bcrypt($pin);
        $this->save();
    }

    public function requiresPinApproval(): bool
    {
        return in_array($this->role, [
            self::ROLE_KOORDINATOR,
            self::ROLE_MANAGER,
            self::ROLE_SUPER_ADMIN,
            self::ROLE_GM_CEO,
        ]);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'created_by');
    }

    public function approvedRequests(): HasMany
    {
        return $this->hasMany(MeetingRequest::class, 'approved_by');
    }

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_GM_CEO = 'gm_ceo';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_KOORDINATOR = 'koordinator';
    public const ROLE_STAFF = 'staff';
    public const ROLE_STAFF_IT = 'staff_it';
    public const ROLE_STAFF_CREATIVE = 'staff_creative';
    public const ROLE_STAFF_HOST = 'staff_host';
    public const ROLE_STAFF_ADMIN = 'staff_admin';

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isGmCeo(): bool
    {
        return $this->role === self::ROLE_GM_CEO;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isKoordinator(): bool
    {
        return $this->role === self::ROLE_KOORDINATOR;
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isStaffIt(): bool
    {
        return $this->role === self::ROLE_STAFF_IT;
    }

    public function isStaffCreative(): bool
    {
        return $this->role === self::ROLE_STAFF_CREATIVE;
    }

    public function isStaffHost(): bool
    {
        return $this->role === self::ROLE_STAFF_HOST;
    }

    public function isStaffAdmin(): bool
    {
        return $this->role === self::ROLE_STAFF_ADMIN;
    }

    public function roleLevel(): int
    {
        return match ($this->role) {
            self::ROLE_SUPER_ADMIN => 5,
            self::ROLE_GM_CEO => 4,
            self::ROLE_MANAGER => 3,
            self::ROLE_KOORDINATOR => 2,
            self::ROLE_STAFF => 1,
            self::ROLE_STAFF_IT => 1,
            self::ROLE_STAFF_CREATIVE => 1,
            self::ROLE_STAFF_HOST => 1,
            self::ROLE_STAFF_ADMIN => 1,
            default => 0,
        };
    }

    /** @deprecated Use isSuperAdmin() instead */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    /** @deprecated Use isGmCeo() instead */
    public function isDireksi(): bool
    {
        return $this->isGmCeo();
    }

    /** @deprecated Use isStaff() instead */
    public function isKaryawan(): bool
    {
        return $this->isStaff();
    }

    public function canCreateData(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_GM_CEO]);
    }

    public function canUpdateData(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_GM_CEO, self::ROLE_MANAGER]);
    }

    public function canDeleteData(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_GM_CEO]);
    }

    public function canViewAll(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_GM_CEO, self::ROLE_MANAGER, self::ROLE_KOORDINATOR]);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'pin_hash' => 'hashed',
        ];
    }
}
