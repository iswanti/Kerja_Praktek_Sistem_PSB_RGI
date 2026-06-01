<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'nama' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'layout-dashboard',
                'order' => 1,
            ],
            [
                'nama' => 'Manajemen',
                'route' => null,
                'icon' => 'settings',
                'order' => 2,
                'children' => [
                    [
                        'nama' => 'Pengguna',
                        'route' => 'admin.users.index',
                        'icon' => 'users',
                        'order' => 1,
                    ],
                    [
                        'nama' => 'Kewenangan',
                        'route' => 'admin.roles.index',
                        'icon' => 'shield-check',
                        'order' => 2,
                    ],
                    [
                        'nama' => 'Data Alumni',
                        'route' => 'admin.alumni.index',
                        'icon' => 'clipboard-list',
                        'order' => 3,
                    ],

                ],
            ],
            [
                'nama' => 'Control Setting',
                'route' => null,
                'icon' => 'sliders-horizontal',
                'order' => 3,

                'children' => [
                    [
                        'nama' => 'Gelombang',
                        'route' => 'admin.gelombang.index',
                        'icon' => 'calendar-range',
                        'order' => 1,
                    ],

                    [
                        'nama' => 'Jadwal Wawancara',
                        'route' => 'admin.jadwal-wawancara.index',
                        'icon' => 'video',
                        'order' => 2,
                    ],

                ]
            ],
            [
                'nama' => 'Pendaftaran',
                'route' => 'admin.pendaftaran.index',
                'icon' => 'clipboard-list',
                'order' => 4,
            ],

            [
                'nama' => 'Bank Soal',
                'route' => 'admin.soal.index',
                'icon' => 'file-check',
                'order' => 5,
            ],

            [
                'nama' => 'Hasil Pretest',
                'route' => 'admin.pretest.index',
                'icon' => 'clipboard-check',
                'order' => 6,
            ],

            [
                'nama' => 'Wawancara',
                'route' => 'admin.wawancara.index',
                'icon' => 'messages-square',
                'order' => 7,
            ],

            [
                'nama' => 'Seleksi',
                'route' => 'seleksi.index',
                'icon' => 'badge-check',
                'order' => 8,
            ],

            [
                'nama' => 'Pengumuman',
                'route' => 'pengumuman.publik',
                'icon' => 'megaphone',
                'order' => 9,
            ],
        ];

        foreach ($menus as $menu) {
            if ($menu['route'] === null) {
                $parent = Menu::updateOrCreate(
                    ['nama' => $menu['nama']],
                    [
                        'parent_id' => null,
                        'route' => null,
                        'icon' => $menu['icon'],
                        'order' => $menu['order'],
                        'is_active' => true,
                    ]
                );
            } else {
                $parent = Menu::updateOrCreate(
                    ['route' => $menu['route']],
                    [
                        'nama' => $menu['nama'],
                        'parent_id' => null,
                        'icon' => $menu['icon'],
                        'order' => $menu['order'],
                        'is_active' => true,
                    ]
                );
            }

            if (isset($menu['children'])) {
                foreach ($menu['children'] as $child) {
                    Menu::updateOrCreate(
                        ['route' => $child['route']],
                        [
                            'nama' => $child['nama'],
                            'parent_id' => $parent->id,
                            'icon' => $child['icon'],
                            'order' => $child['order'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        }
    }
}