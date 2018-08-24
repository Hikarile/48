<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\User;
use App\Model\Album;
use Validator;

class image
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
        $album_id = explode('/', $request->getpathInfo())[2];
        $token = $request->header('authorization');

        $rules = ['authorization' => 'required'];
        $message = ['authorization.required' => '拒絕存取'];
        $validator = Validator::make($request->header(), $rules, $message);
        if($validator->fails()){
            abort(400, $validator->errors()->first());
        }

        $user = User::where('token', $token)->firstOrFail();
        $album = Album::where('user_id', $user->id)->where('id', $album_id)->firstOrFail();
        
        return $next($request);
    }
}
