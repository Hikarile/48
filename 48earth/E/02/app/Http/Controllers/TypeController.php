<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Booking;

class TypeController extends Controller
{
    public function type(){
        $type = Type::get();
        return response()->view('type.type', compact('type'), 200);
    }

    public function add_fix($id = ''){
        $type = Type::find($id) ?: new Type;
        return response()->view('type.add', compact('type'), 200);
    }

    public function create(Request $request, $id = 0){
        $data = [
            'name' => $request->name,
            'speed' => $request->speed
        ];
        if($id == ''){
            Type::create($data);
        }else{
            Type::where('id', $id)->update($data);
        }
        return redirect('login/type')->with([
            'message' => $id ? '修改成功' : '新增成功'
        ]);
    }
    
    public function delete($id = ''){
        Type::where('id', $id)->delete();
        return redirect('login/type')->with(['message' => '刪除成功']);
    }

}
