<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Account;
use App\Model\Ablum;
use App\Model\Image;

class AccountController extends Controller
{
    private function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 4, 7));
    }

    public function account_add(Request $request){
        $data = json_decode(json_encode(simplexml_load_string($request->getContent())), true);
        if(Account::where('account', $data['account'])->first()){
            return abort(400, '此帳號已被註冊'); 
        }

        $data['account_id'] = $this->token();
        $data['token'] = $this->token();
        Account::create($data);

        return response()->view('account.account_add', $data, 200)->header('Content-type', 'application/xml');
    }

    public function account_get(Request $request, $account_id){
        $account = Account::where('account_id', $account_id)->firstOrFail();
        return response()->view('account.account_get', compact('account'), 200)->header('Content-type', 'application/xml');
    }
}
