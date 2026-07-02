<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefAdminRole extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function administratorRoles()
    {
        return $this->hasMany(AdministratorRole::class, 'role_id');
    }

    public function administrators()
    {
        return $this->belongsToMany(
            Administrator::class,
            'administrator_roles',
            'role_id',
            'administrator_id'
        );
    }
}

