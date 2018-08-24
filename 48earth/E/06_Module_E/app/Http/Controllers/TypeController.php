<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;

class TypeController extends Controller
{
    public function index(){
        $types = Type::all();
        return view('type.index', compact('types'));
    }
    public function add_fix($id = ''){
        $types = Type::find($id)?: new Type;
        return view('type.add_fix', compact('types'));
    }
    public function create(Request $request, $id = ''){
        $request->validate([
            'name' => 'required|unique:types,name,'.$id
        ],[
            'name.unique' => '車種名稱不能重複'
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

        return redirect('/login/type')->with([
            'message'=> $id? '修改成功': '新增成功'
        ]);
    }
    public function delete($id){
        $type = Type::find($id);
        $trains = Train::where('type_id', $type->id)->get();
        foreach($trains as $train){
            $train->stops()->delete();
            $train->books()->delete();
            $train->delete();
        }
        $type->delete();
        
        return redirect('/login/type')->with([
            'message'=> '刪除成功'
        ]);
    }
}
