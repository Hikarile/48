<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Booking;

class StationController extends Controller
{
    public function index(){
        $stations = Station::all();
        return response()->view('station.index', compact('stations'), 200);
    }

    public function add_fix($id = ''){
        $stations = Station::find($id) ?? new Station;
        return response()->view('station.add_fix', compact('stations'), 200);
    }

    public function create(Request $request, $id = ''){
        $request->validate([
            'chinese' => 'required|unique:stations,chinese,' . $id,
            'english' => 'required|regex:/^[A-Za-z\-]+$/|unique:stations,english,' . $id,
        ], [
            'chinese.unique' => '車站名稱不可重複',
            'english.regex' => '只能有 A-Z 及-',
            'english.unique' => '英文名稱不可重複',
        ]);

        $data = [
            'chinese' => $request->chinese,
            'english' => $request->english,
        ];

        if($id == ''){
            Station::create($data);
        }else{
            Station::where('id', $id)->update($data);
        }
        
        return redirect('login/station')->with([
            'message' => $id ? '修改成功' : '新增成功'
        ]);
    }

    public function delete($id = ''){
        Station::where('id', $id)->delete();
        return redirect('login/station')->with([
            'message' => '刪除成功'
        ]);
    }
}
