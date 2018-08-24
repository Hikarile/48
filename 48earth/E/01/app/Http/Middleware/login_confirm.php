<?php

namespace App\Http\Middleware;

use Closure;

class login_confirm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(session('login')){
            return $next($request);
        }else{
            session()->flash('error', '尚未登入');
            return redirect('login');
        }
    }
}
