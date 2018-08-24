<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Account;

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
        if(! $token = $request->header('Authorization')){
            return abort(404, '');
        }
        $token = explode(' ', $token)[1];
        if($account = Account::where('token', $token)->firstOrFail()){
            $request->request->add(compact('account'));
        }
        return $next($request);
    }
}
