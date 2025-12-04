<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // kalau mau pakai untuk auth
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Stub method: implementasi nanti
    public function register(array $data)
    {
        // TODO: implement
    }

    public function login(string $email, string $password)
    {
        // TODO: implement
    }
}
