<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Check if the user has verified their email address.
     * When email verification is disabled in admin settings, always return true.
     */
    public function hasVerifiedEmail(): bool
    {
        // Check if email verification is enabled in admin settings
        // Setting::get() returns a real bool for boolean-type settings
        $verificationEnabled = Setting::get('email_verification_enabled', false);

        if (!$verificationEnabled) {
            return true; // Bypass verification when disabled
        }

        return !is_null($this->email_verified_at);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'whatsapp',
        'avatar',
        'latitude',
        'longitude',
        'city',
        'state',
        'country',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    // Relationships
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function savedSearches(): HasMany
    {
        return $this->hasMany(SavedSearch::class);
    }

    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    // Get favorite cars
    public function favoriteCars()
    {
        return $this->hasManyThrough(Car::class, Favorite::class, 'user_id', 'id', 'id', 'car_id');
    }

    // Check if user has favorited a car
    public function hasFavorited(Car $car): bool
    {
        return $this->favorites()->where('car_id', $car->id)->exists();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
