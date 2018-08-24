<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\User;
use App\Model\Type;
use App\Model\Station;
use App\Model\Train;
use App\Model\Stop;
use App\Model\Booking;

class UserController extends Controller
{
    
    public function login(Request $request){ //登入後台
        return response()->view('login.login', [], 200);
    }
    public function login_confirm(Request $request){ //登入判斷
        $rel = [
            'ac' => 'required',
            'ps' => 'required'
        ];
        $message = [
            'ac.required' => '未填寫完成',
            'ps.required' => '未填寫完成',
        ];
        $request->validate($rel, $message);
        
        $user = User::where('ac', $request->ac)->firstOrFail();
        if($user->ps == $request->ps){
            session()->push('login', true);
            return redirect('/home');
        }else{
            abort(200, '登入失敗');
        }
    }

    public function logout(){ //登出
        session()->forget('login');
        return redirect('/login');
    }

}
