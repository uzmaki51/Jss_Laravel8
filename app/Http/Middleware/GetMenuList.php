<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Menu;
use Session;

class GetMenuList
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if(Session::get('menusList') == null) {
            $tbl = new Menu();
            $menus = $tbl->getMenuList();
        
            Session::put('menusList', $menus);
        // }
        
        return $next($request);
    }
}
