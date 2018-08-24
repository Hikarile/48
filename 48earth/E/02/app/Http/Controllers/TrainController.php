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
    public function train(){
        $train = Train::get();
        $stations = Station::all();
        return response()->view('train.train', compact('train', 'stations'), 200);
    }

    public function add_fix($id = ''){
        $types = Type::get();
        $days = ['', '一', '二', '三', '四', '五', '六', '日'];

        $stations = Station::all();
        $train = Train::find($id) ?: new Train;
        if($id){
            return response()->view('train.fix', compact('train', 'types', 'days', 'stations'), 200);
        }else{
            return response()->view('train.add', compact('train', 'types', 'days', 'stations'), 200);
        }
    }

    public function create(Request $request, $id = 0){
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
        $train = Train::where('id', $id)->firstOrFail();
        $train->stops()->delete();
        $train->bookings()->delete();
        $train->delete();
        return redirect('login/train')->with(['message' => '刪除成功']);
    }

    public function lookup(){
        $stations = Station::all();
        $types = Type::all();
        $trains = Train::all();
        return response()->view('train.lookup', compact('stations', 'types', 'trains'), 200);
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
