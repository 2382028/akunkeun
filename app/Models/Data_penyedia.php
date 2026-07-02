<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Data_penyedia extends Authenticatable
{
        protected $guarded = [];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
