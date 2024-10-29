<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use LdapRecord\Laravel\Auth\LdapAuthenticatable;
// use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\LdapImportable;
use LdapRecord\Laravel\Auth\HasLdapUser;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
// use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements LdapAuthenticatable, LdapImportable
{
    use HasFactory, Notifiable,  AuthenticatesWithLdap, HasLdapUser; // Correct trait here
    // use HasRoles; 
    

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role_id',
        'contact_number',
        'department_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    
    public function assets()
    {
        return $this->hasMany(Asset::class, 'user_id');
    }

    public function assetHistories()
    {
        return $this->hasMany(AssetHistory::class);
    }

    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasRole($role)
    {
        return $this->roles()->where('role_name', $role)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    public function assignRole($role)
    {
        return $this->roles()->sync([$role]);
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class);
    }

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
