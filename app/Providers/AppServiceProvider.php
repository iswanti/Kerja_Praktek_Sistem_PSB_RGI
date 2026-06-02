<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                $menus = Menu::with([
                        'children.permissions',
                        'permissions',
                    ])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->where(function ($query) use ($user) {

                        $query->whereHas('permissions', function ($q) use ($user) {
                            $q->where('role_id', $user->role_id)
                              ->where(function ($p) {
                                  $p->where('can_read', true)
                                    ->orWhere('can_create', true);
                              });
                        })

                        ->orWhereHas('children.permissions', function ($q) use ($user) {
                            $q->where('role_id', $user->role_id)
                              ->where(function ($p) {
                                  $p->where('can_read', true)
                                    ->orWhere('can_create', true);
                              });
                        });

                    })
                    ->orderBy('order')
                    ->get();

                $view->with('sidebarMenus', $menus);
            }
        });
    }
}