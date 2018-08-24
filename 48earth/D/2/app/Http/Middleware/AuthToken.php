<?php

namespace App\Http\Middleware;

use Closure;

class AuthToken
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
        $auth = explode(' ', $request->header('Authorization'));
        $token = '';
        foreach($auth as $item){
            if(strlen($item) == 7){
                $token = $item;
                break;
            }
        }
        $account = \App\Account::where('token', $token)->firstOrFail();
        $request->request->add(compact('account'));
        return $next($request);
    }
}
