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
        $tbl = new Menu();
		$menus = $tbl->getMenuList();
        
        // var_dump($menus);die;
        if(Session::get('menusList') == null)
            Session::put('menusList', $menus);
        
        return $next($request);
    }
}
