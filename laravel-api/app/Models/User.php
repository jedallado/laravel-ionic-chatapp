<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enumerations\Models\UserModelEnum;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    /*protected $fillable = [
        'username',
        'email',
        'password',
    ];*/

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    /*protected $hidden = [
        'password',
        'remember_token',
    ];*/

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

    public function profile() {
        return $this->hasOne(UserProfile::class);
    }

    public function verificationCode() {
        return $this->hasOne(EmailVerificationCode::class);
    }

    public function getFillable()
    {
        return UserModelEnum::fillable();
    }

    public function getHidden()
    {
        return UserModelEnum::hidden();
    }

    public function getId() {
        return $this->{UserModelEnum::getId()};
    }

    public function getEmail() {
        return $this->{UserModelEnum::getEmail()};
    }

    public function isPasswordValid(string $password): bool {
        return Hash::check($password, $this->{UserModelEnum::getPassword()});
    }

    public function isVerified() {
        return $this->{UserModelEnum::getEmailVerifiedAt()} !== null;
    }
}
