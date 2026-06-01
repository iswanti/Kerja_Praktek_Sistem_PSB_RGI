<?php

namespace App\Models;
use App\Models\Cabang;
use App\Models\Role;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'unsur_wawancara',
        'cabang_id',
        'jurusan_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasMenuPermission($menuName, $action = 'read'): bool
    {
        $menu = \App\Models\Menu::where('nama', $menuName)->first();

        if (!$menu || !$this->role) {
            return false;
        }

        $permission = $this->role
            ->permissions()
            ->where('menu_id', $menu->id)
            ->first();

        if (!$permission) {
            return false;
        }

        return match ($action) {
            'read'     => (bool) $permission->can_read,
            'create'   => (bool) $permission->can_create,
            'update'   => (bool) $permission->can_update,
            'delete'   => (bool) $permission->can_delete,
            'download' => (bool) $permission->can_download,
            default    => false,
        };
    }
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function canReadMenu($menuName)
    {
        return $this->hasMenuPermission($menuName, 'read');
    }

    public function canCreateMenu($menuName)
    {
        return $this->hasMenuPermission($menuName, 'create');
    }

    public function canUpdateMenu($menuName)
    {
        return $this->hasMenuPermission($menuName, 'update');
    }

    public function canDeleteMenu($menuName)
    {
        return $this->hasMenuPermission($menuName, 'delete');
    }
   
    public function canDownloadMenu($menuName)
    {
        return $this->hasMenuPermission($menuName, 'download');
    }
}
