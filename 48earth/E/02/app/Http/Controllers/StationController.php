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
        $station = Station::get();
        return response()->view('station.station', compact('station'), 200);
    }

    public function add_fix($id = ''){
        $station = Station::find($id) ?: new Station;
        return response()->view('station.add', compact('station'), 200);
    }

    public function create(Request $request, $id = 0){
        $data = [
            'chinese' => $request->chinese,
            'english' => $request->english
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
        return redirect('login/station')->with(['message' => '刪除成功']);
    }
}
