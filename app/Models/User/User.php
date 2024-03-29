<?php

namespace App\Models\User;

use App\Enums\Account;
use App\Models\Admin\Plan;
use App\Notifications\UserResetPasswordNotification;
use App\Notifications\UserVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use LucasDotVin\Soulbscription\Models\Concerns\HasSubscriptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasSubscriptions;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'name',
        'surname',
        'email',
        'password',
        'last_login_at',
        'last_login_ip',
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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime',
        'status' => Account::class,
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserVerifyEmail);
    }

    /**
     * Kullanıcı Üye Olduğunda
     * Ücretsiz Plana eklenir.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            $user->subscribeTo(Plan::where('name', 'ucretsiz-plan')->first());
        });
    }
}
