<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use App\Model\Ablum;
use App\Model\Image;
use Validator;

class token
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
		$rules = [
            'authorization' => 'required',
        ];
        $messages = [
            'authorization.required' => '拒絕存取',
        ];

		$validator = Validator::make($request->header(), $rules, $messages);
		if($validator->fails()){
			abort(400, $validator->errors()->first());
		}
		
        $user = User::where('token', $request->header('authorization'))->firstOrFail();
        return $next($request);
    }
}
