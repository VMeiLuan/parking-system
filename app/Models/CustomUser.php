<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomUser extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'custom_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'confirm_password',
        'role_id'
    ];

    protected $hidden = [
        'password',
        'confirm_password'
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
