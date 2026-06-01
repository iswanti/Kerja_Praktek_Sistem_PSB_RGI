<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMenuPermission extends Model
{
    protected $fillable = [
        'role_id',
        'menu_id',
        'can_read',
        'can_create',
        'can_update',
        'can_delete',
        'can_download',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
