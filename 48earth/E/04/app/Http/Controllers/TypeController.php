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
        $types = Type::all();
        return view('type.index', compact('types'));
    }
    public function add_fix($id = ''){
        $type =  Type::find($id)?:new Type;
        return view('type.add_fix', compact('type'));
    }
    public function create(Request $request, $id = ''){
        $request->validate([
            'name' => 'required|unique:types,name,' . $id,
        ], [
            'name.unique' => '車站名稱不可重複',
        ]);

        $data = [
            'name' => $request->name,
            'speed' => $request->speed,
        ];
        if($id == ''){
            Type::create($data);
        }else{
            Type::find($id)->update($data);
        }

        return redirect('login/type')->with([
            'message' => $id ? '修改成功' : '新增成功'
        ]);
    }
    public function delete($id){
        Type::find($id)->delete();
        return redirect('login/type')->with([
            'message' => '刪除成功'
        ]);
    }
}
