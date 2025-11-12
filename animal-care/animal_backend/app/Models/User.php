<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userId';

    /**
     * Kolom yang boleh diisi (mass assignable)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (misal ke JSON)
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast otomatis atribut ke tipe tertentu
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

    public function antrians()
{
    return $this->hasMany(Antrian::class, 'userId', 'userId');
}
    
    // public function antrians()
    // {
    //     return $this->hasMany(Antrian::class, 'userId', 'userId');
    // }

    // public function histories()
    // {
    //     return $this->hasMany(History::class, 'userId', 'userId');
    // }


}
