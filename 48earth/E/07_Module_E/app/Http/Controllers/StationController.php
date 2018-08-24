<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;
class StationController extends Controller
{
    public function index(){
        $stations = Station::all();
        return view('station.index', compact('stations'));
    }
    public function add_fix($id = ''){
        $stations = Station::find($id) ?: new Station;
        return view('station.add_fix', compact('stations'));
    }
    public function create(Request $request, $id = ''){
        $request->validate([
            'chinese' => 'required|unique:stations,chinese,'.$id,
            'english' => 'required|regex:/^[A-Za-z\-]+$/|unique:stations,english,'.$id,
        ],[
            'chinese.unique' => '車站名稱不能一樣',
            'english.unique' => '英文名稱不能一樣',
            'english.regex' => '只能A-Za-z及-',
        ]);
        $data = [
            'chinese' => $request->chinese,
            'english' => $request->english,
        ];
        if($id == ''){
            Station::create($data);
        }else{
            Station::find($id)->update($data);
        }
        return redirect('login/station')->with([
            'message' => $id?'修改成功':'新增成功'
        ]);
    }
    public function delete($id = ''){
        $stops = Stop::where('station_id', $id)->get();
        if(count($stops)){
            return redirect('login/station')->with([
                'message' => '禁止刪除',
                'error' => 'danger',
            ]);
        }else{
            $station = Station::find($id)->delete();
            return redirect('login/station')->with([
                'message' => '刪除成功'
            ]);
        }
    }
}
