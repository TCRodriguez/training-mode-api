<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function games(){
    //     return $this->belongsToMany(Game::class);
    // }

    public function combos()
    {
        return $this->hasMany(CharacterCombo::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function generateVerificationUrl()
    {
        // return URL::signedRoute('verification.verify', [
        //     'id' => $this->getKey(),
        //     'hash' => sha1($this->getEmailForVerification())
        // ]);
        $expiration = Carbon::now()->addMinutes(60);

        return URL::temporarySignedRoute(
            'verification.verify',
            $expiration,
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification())
            ]
        );
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}
