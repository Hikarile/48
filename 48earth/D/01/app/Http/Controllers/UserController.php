<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Album;
use App\Model\Image;
use Validator;


class UserController extends Controller
{
	
	private function token(){
		$all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
		shuffle($all);
		return implode('', array_slice($all,4,7));
	}
	
    public function user_add(Request $request){
		$xml = simplexml_load_string($request->getContent());
		$array = json_decode(json_encode($xml), true);
		
		if(count(array_diff_key(['account'=>'', 'bio'=>''], $array))){
			abort(400, '資料未填寫完成');
		}
		
		$rules = [
            'account' => 'required|unique:users',
            'bio' => 'required',
        ];
        $messages = [
            'account.required' => '無效的輸入資料',
            'account.unique' => '此帳號已經被註冊',
            'bio.required' => '無效的輸入資料',
        ];
		
		$validator = Validator::make($array, $rules, $messages);
		if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }
		
		$array['token'] = $this->token();
		$array['created'] = time();
		$user = User::create($array);
		
		return response()->view('user.user_add', ['token' => $user->token], 200)
						->header('Content-type', 'application/xml');
	}
	
	public function user_get(Request $request, $user_id){
		$user = User::where('id', $user_id)->where('token', $request->header('authorization'))->firstOrFail();
		$album = Album::where('user_id', $user->id)->get();
		
		$data = [
			'account' => $user->account,
			'bio' => $user->bio,
			'created' => $user->created,
		];
		foreach($album as $val){
			$data['albums'][] = [
				'id' => $val->token,
				'count' => $val->count
			];
		}
		
		return response()->view('user.user_get', $data, 200)
                         ->header('content-type', 'application/xml');
	}
	
}
