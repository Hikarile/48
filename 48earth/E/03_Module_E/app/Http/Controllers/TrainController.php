<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Booking;

class TrainController extends Controller
{
    public function index(){
        $trains = Train::all();
        $stations = Station::all();
        return response()->view('train.index', compact('trains', 'stations'), 200);
    }

    public function add_fix($id = 0){

        $trains = Train::find($id) ?: new Train;
        $types = Type::get();
        $stations = Station::get();
        $days = ['', '一', '二', '三', '四', '五', '六', '日'];
        
        if($id){
            return response()->view('train.fix', compact('trains', 'types', 'days', 'stations'), 200);
        }else{
            return response()->view('train.add', compact('trains', 'types', 'days', 'stations'), 200);
        }
    }

    public function create(Request $request, $id = ''){
        $request->validate([
            'name' => 'required|unique:trains,name,' . $id,
            'day' => 'required',
            'start_time' => 'required',
        ], [
            'name.unique' => '列車名稱不可重複',
        ]);
        
        if($id == 0){
            $train = Train::create([
                'type_id' => $request->type_id,
                'name' => $request->name,
                'day' => implode(',', $request->day),
                'start_time' => $request->start_time,
                'number' => $request->number,
                'people' => $request->people
            ]);
        }else{
            $train = Train::where('id', $id)->firstOrFail();
            $train->update([
                'type_id' => $request->type_id,
                'name' => $request->name,
                'day' => implode(',', $request->day),
                'start_time' => $request->start_time,
                'number' => $request->number,
                'people' => $request->people
            ]);
        }

        $train->stops()->delete();
        $train->bookings()->delete();
        foreach ($request->stop_station as $key => $val) {
            $train->stops()->create([
                'station_id' => $val,
                'time' => $request->stop_time[$key],
                'stop_time' => $request->stop_stop_time[$key],
                'money' => $request->stop_money[$key],
            ]);
        }
        
        return redirect('login/train')->with([
            'message' => $id ? '修改成功' : '新增成功'
        ]);
    }

    public function delete($id = ''){
        Train::where('id', $id)->delete();
        return redirect('login/train')->with(['message' => '刪除成功']);
    }

    public function lookup(){
        $stations = Station::get();
        $types = Type::get();
        return response()->view('train.lookup', compact('stations', 'types'), 200);
    }

    public function see($name = ''){
        $gets = Train::where('name', $name)->get();
        $trains = Train::get();
        $stations = Station::get();
        return response()->view('train.see', compact('trains', 'gets', 'stations'), 200);
    }

    public function select($type = '', $day = '', $from = '', $to = ''){
        $stations = Station::all();
        $types = Type::where('name', $type)->first();

        $from = Station::where('english', $from)->firstOrFail();
        $to = Station::where('english', $to)->firstOrFail();
        $trains = Train::where('type_id', $types->id)->get();

        foreach ($trains as $key => $train) {
            
            if (! $train->day($day)){
                $trains->forget($key);
                continue;
            }
        
            $from_stop = $train->getstop($from->id);
            $to_stop = $train->getstop($to->id);
            if ($from_stop == '' || $to_stop == '' || $from_stop->id >= $to_stop->id) {
                $trains->forget($key);
                continue;
            }
        }
        
        if(count($trains)){
            return response()->view('train.select', compact('trains', 'stations', 'types'), 200);
        }else{
            return response()->view('train.select', compact('trains', 'stations', 'types'), 200);
        }
    }
}
