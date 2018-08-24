<?php

namespace App\Http\Middleware;

use Closure;
use App\Account;

class Token
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
        if($request->header('Authorization') == ''){
            return abort(403);
        }
        $token = explode(' ', $request->header('Authorization'))[1];
        $account = Account::where('token', $token)->firstOrFail();
        $request->request->add(compact('account'));
        return $next($request);
    }
}
