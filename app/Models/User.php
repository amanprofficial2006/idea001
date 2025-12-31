<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'user_uid',

        'name',
        'email',
        'phone',
        'password',
        'profile_image',

        'is_active',
        'is_blocked',
        'blocked_reason',

        'is_online',
        'last_seen_at',

        'last_latitude',
        'last_longitude',
        'location_updated_at',

        'wallet_balance',
        'total_earned',

        'rating_avg',
        'rating_count',

        'is_verified',
        'verification_type',

        'device_type',
        'device_token',

        'last_login_at',
    ];

    /**
     * Hidden attributes (security)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'location_updated_at' => 'datetime',
        'last_login_at' => 'datetime',

        'is_active' => 'boolean',
        'is_blocked' => 'boolean',
        'is_online' => 'boolean',
        'is_verified' => 'boolean',

        'wallet_balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'rating_avg' => 'decimal:2',
    ];

    /**
     * Boot method
     * Auto-generate public user UID
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->user_uid)) {
                $user->user_uid = 'USR-' . strtoupper(Str::random(8));
            }
        });
    }

    /* ======================================================
     | Accessors
     ====================================================== */

    /**
     * Get full profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }

        return asset('images/default-avatar.png');
    }

    /**
     * Get full cover image URL
     */
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        return null;
    }

    /**
     * Check if user is available for tasks
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && !$this->is_blocked && $this->is_online;
    }

    /* ======================================================
     | Scopes (useful for queries)
     ====================================================== */

    /**
     * Only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('is_blocked', false);
    }

    /**
     * Only online users
     */
    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    /**
     * Verified users
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /* ======================================================
     | Helper methods
     ====================================================== */

    /**
     * Mark user online
     */
    public function markOnline(): void
    {
        $this->update([
            'is_online' => true,
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Mark user offline
     */
    public function markOffline(): void
    {
        $this->update([
            'is_online' => false,
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Update user location
     */
    public function updateLocation(float $lat, float $lng): void
    {
        $this->update([
            'last_latitude' => $lat,
            'last_longitude' => $lng,
            'location_updated_at' => now(),
        ]);
    }
}
