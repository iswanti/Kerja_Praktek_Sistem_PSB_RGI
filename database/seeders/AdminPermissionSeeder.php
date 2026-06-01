<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Menu;
use App\Models\RoleMenuPermission;

class AdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = Role::where('nama', 'Superadmin')->first();
        $adminRole = Role::where('nama', 'Admin')->first();

        /*
        |--------------------------------------------------------------------------
        | Superadmin: full akses semua menu admin
        |--------------------------------------------------------------------------
        */
        if ($superadminRole) {
            $superadminRoutes = [
                'dashboard',
                'admin.users.index',
                'admin.roles.index',
                'admin.gelombang.index',
                'admin.jadwal-wawancara.index',
                'admin.pendaftaran.index',
                'admin.wawancara.index',
                'admin.soal.index',
                'admin.pretest.index',
                'admin.alumni.index',
            ];

            $menus = Menu::whereIn('route', $superadminRoutes)->get();

            foreach ($menus as $menu) {
                RoleMenuPermission::updateOrCreate(
                    [
                        'role_id' => $superadminRole->id,
                        'menu_id' => $menu->id,
                    ],
                    [
                        'can_read' => true,
                        'can_create' => true,
                        'can_update' => true,
                        'can_delete' => true,
                        'can_download' => true,
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Admin: akses sesuai cabang, dibatasi permission
        |--------------------------------------------------------------------------
        */
        if ($adminRole) {
            $adminPermissions = [
                'dashboard' => [
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'download' => true,
                ],

                'admin.pendaftaran.index' => [
                    'read' => true,
                    'create' => false,
                    'update' => true,
                    'delete' => false,
                    'download' => true,
                ],

                'admin.wawancara.index' => [
                    'read' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => false,
                    'download' => true,
                ],

                'admin.pretest.index' => [
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'download' => true,
                ],

                'admin.users.index' => [
                    'read' => false,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'download' => false,
                ],

                'admin.roles.index' => [
                    'read' => false,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'download' => false,
                ],

                'admin.gelombang.index' => [
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'download' => false,
                ],

                'admin.jadwal-wawancara.index' => [
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'download' => false,
                ],

                'admin.soal.index' => [
                    'read' => true,
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                    'download' => false,
                ],
            ];

            foreach ($adminPermissions as $route => $permission) {
                $menu = Menu::where('route', $route)->first();

                if (!$menu) {
                    continue;
                }

                RoleMenuPermission::updateOrCreate(
                    [
                        'role_id' => $adminRole->id,
                        'menu_id' => $menu->id,
                    ],
                    [
                        'can_read' => $permission['read'],
                        'can_create' => $permission['create'],
                        'can_update' => $permission['update'],
                        'can_delete' => $permission['delete'],
                        'can_download' => $permission['download'],
                    ]
                );
            }
        }
    }
}