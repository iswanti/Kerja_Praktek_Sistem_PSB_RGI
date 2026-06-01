<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $menu, string $ability = 'read'): mixed
    {
        abort_unless(auth()->check(), 401);

        $allowed = match($ability) {
            'read'     => auth()->user()->canReadMenu($menu),
            'create'   => auth()->user()->canCreateMenu($menu),
            'update'   => auth()->user()->canUpdateMenu($menu),
            'delete'   => auth()->user()->canDeleteMenu($menu),
            'download' => auth()->user()->canDownloadMenu($menu),
            default    => false,
        };

        abort_unless($allowed, 403);

        return $next($request);
    }
}
