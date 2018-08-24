<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Album;
use App\Image;

class AccountController extends Controller
{
    public function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 1, 7));
    }

    public function add(Request $request){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        $account = Account::where('account', $data['account'])->first();
        
        if($account != null){
            return abort(400, '此帳號已被註冊');
        }
        $data['account_id'] = $this->token();
        $data['token'] = $this->token();
        
        Account::create($data);
        return response()->view('account.add', $data, 200)->header('Content-Type', 'application/xml');
    }
    public function get(Request $request, $account_id = ''){
        $account = Account::where('account_id', $account_id)->firstOrFail();
        return response()->view('account.get', compact('account'), 200)->header('Content-Type', 'application/xml');
    }
}
