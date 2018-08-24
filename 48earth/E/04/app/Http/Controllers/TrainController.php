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
        $trains = Train::all();
        $stations =  Station::all();
        return view('train.index', compact('trains', 'stations'));
    }
    public function add_fix($id = ''){

        $trains = Train::find($id) ?: new Train;
        $types =  Type::all();
        $stations =  Station::all();
        $days = ['一', '二', '三', '四','五', '六', '日'];

        if($id == ''){
            return view('train.add', compact('trains', 'types', 'stations', 'days'));
        }else{
            return view('train.fix', compact('trains', 'types', 'stations', 'days'));
        }
    }
    public function create(Request $request, $id = ''){
        $request->validate([
            'code' => 'required|regex:/^[0-9]+$/|unique:trains,code,' . $id,
            'day' => 'required'
        ], [
            'code.unique' => '列車編號不可重複',
        ]);

        $data = [
            'type_id' => $request->type_id,
            'code' => $request->code,
            'day' => implode(',', $request->day),
            'start_time' => $request->start_time,
            'car' => $request->car,
            'people' => $request->people,
        ];
        if($id == ''){
            $train = Train::create($data);
        }else{
            $train = Train::find($id);
            $train->update($data);
        }

        $train->stops()->delete();
        foreach($request->station_id  as $key => $val){
            $data = [
                'station_id' => $val,
                'time' => $request->time[$key],
                'stop_time' => $request->stop_time[$key],
                'money' => $request->money[$key],
            ];
            $train->stops()->create($data);
        }

        return redirect('login/train')->with([
            'message' => $id ? '修改成功' : '新增成功'
        ]);
    }
    public function delete($id){
        $train = Train::find($id);
        $train->stops()->delete();
        $train->delete();
        return redirect('login/train')->with([
            'message' => '刪除成功'
        ]);
    }
    public function index(){
        $stations =  Station::all();
        $types =  Type::all();
        return view('train.train', compact('types', 'stations'));
    }
    public function train_index($type = '', $day = '', $from = '', $to = ''){
        $trains = Train::where('type_id', $type)->get();
        $stations = Station::all();
        $types = Type::where('id', $type)->first();

        $from = Station::where('english', $from)->firstOrFail();
        $to = Station::where('english', $to)->firstOrFail();
        
        foreach($trains as $key => $train){
            if(!$train->day($day)){
                $trains->forget($key);
                continue;
            }

            $from_stop = $train->getstop($from->id);
            $to_stop = $train->getstop($to->id);
            if($from_stop == "" || $to_stop == "" || $from_stop->id > $to_stop->id){
                $trains->forget($key);
                continue;
            }
        }
        
        return view('train.see', compact('trains', 'stations', 'types'))->with([
            'message' => '無查詢指定列車',
            'error' => 'danger'
        ]);
    }
    public function select($code = ''){
        $stations = Station::all();
        $trains = Train::all();
        $data = Train::where('code', $code)->first()?: new Train;
        return view('train.select', compact('trains', 'data', 'stations'));
    }
}
