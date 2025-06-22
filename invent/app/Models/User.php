<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'roles_id',
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

    public function roles(){
        return $this->belongsTo(Roles::class);
    }

    public function loans()
{
    return $this->hasMany(Loan::class);
}

// app/Models/User.php

public function hasRole($roleName)
{
    // If $roleName is numeric, we're checking by ID
    if (is_numeric($roleName)) {
        return $this->role_id == $roleName;
    }
    
    // Otherwise check by role name
    return optional($this->role)->name === $roleName;
}

public function isAdmin()
{
    return $this->roles_id == 1;
}

public function isUser()
{
    return $this->roles_id == 2;
}
}
