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
    public function station(){
        $stations = Station::all();
        return view('station.index', compact('stations'));
    }
    public function add_fix($id = ''){
        $station =  Station::find($id)?:new Station;
        return view('station.add_fix', compact('station'));
    }
    public function create(Request $request, $id = ''){
        $request->validate([
            'chinese' => 'required|unique:stations,chinese,' . $id,
            'english' => 'required|regex:/^[A-Za-z\-]+$/|unique:stations,english,' . $id,
        ], [
            'chinese.unique' => '車站名稱不可重複',
            'english.unique' => '英文名稱不可重複',
            'english.unique' => '只能有A-Z及-',
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
            'message' => $id ? '修改成功' : '新增成功'
        ]);
    }
    public function delete($id){
        Station::find($id)->delete();
        return redirect('login/station')->with([
            'message' => '刪除成功'
        ]);
    }
}
