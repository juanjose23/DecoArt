<?php

namespace App\Models;
use Dompdf\Dompdf;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\FilamentUser;
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasRoles;
    use HasPanelShield;
    use HasFactory;
    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'estado',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
      
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
        ];
    }
    
    public function compras()
    {
        return $this->Hasmany(Compras::class);
    }
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (is_null($user->es_interno)) {
                $user->es_interno = true;
            }
        });
    }
    
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url("$this->avatar_url") : null;
    }
      public function setPasswordAttribute($value)
    {
        
        $this->attributes['password'] = $value;
    }
    public static function encryptPassword($password)
    {
        return Hash::make($password);
    }
}
