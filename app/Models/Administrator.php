<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Model;

class Administrator extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function administratorRoles()
    {
        return $this->hasMany(AdministratorRole::class);
    }

    public function roles()
    {
        return $this->belongsToMany(RefAdminRole::class, 'administrator_roles', 'administrator_id', 'role_id');
    }
    public function hasRole($role)
    {
        if (is_array($role)) {
            return $this->roles->pluck('nama_role')->intersect($role)->isNotEmpty();
        }

        return $this->roles->contains('nama_role', $role);
    }
}
