<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Account;

class AccountController extends Controller
{
    use \App\traits\Process;

    public function store(Request $req)
    {
        $data = simplexml_load_string($req->getContent());
        $data = json_decode(json_encode($data), true);
    
        // if(count(array_diff_key($data, ['account'=>'', 'bio'=>'']))){
        //     abort('400', '無效的輸入資料');
        // }

        $rules = [
            'account' => 'required|unique:accounts',
            'bio' => 'required'
        ];
        $messages = [
            'required' => '無效的輸入資料',
            'account.unique' => '此帳號已經被註冊',
        ];
        
        $vali = Validator::make($data, $rules, $messages);
        if($vali->fails()){
            abort(400, $vali->errors()->first());
        }

        $data['token'] = $this->token(7);
        $data['account_id'] = $this->token(7);
        
        $account = Account::create($data);

        return response()->view('success.show', ['status_code'=>200, 'message' => $account->account_id], 200)
                            ->header('Content-type', 'text/plain');
    }

    public function show(Request $req, $account_id)
    {
        $account = Account::where('account_id', $account_id)->firstOrFail();

        $albums = $account->albums;
        foreach ($albums as $k=>$album) {
            $album->count = $album->images()->count();
        }
        return response()
                ->view('success.account-show', compact('account', 'albums'), 200)
                ->header('Content-type', 'application/xml');
    }
}