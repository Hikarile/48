<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;

class TrainController extends Controller
{
    public function index(){
        $trains = Train::all();
        $stations = Station::all();
        return view('train.index', compact('trains', 'stations'));
    }
    public function add(){
        $stations = Station::all();
        $types = Type::all();
        $days =['一', '二', '三', '四', '五', '六', '日'];
        return view('train.add', compact('stations', 'types', 'days'));
    }
    public function fix($id = 0){
        $stations = Station::all();
        $types = Type::all();
        $days =['一', '二', '三', '四', '五', '六', '日'];
        $trains = Train::find($id);
        return view('train.fix', compact('stations', 'types', 'days', 'trains'));
    }
    public function create(Request $request, $id = 0){
        $request->validate([
            'code' => 'required|unique:trains,code,'.$id,
            'day' => 'required'
        ],[
            'code.unique' => '列車編號不能重複',
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
        $train->books()->delete();
        foreach($request->station_id as $key => $id){
            $data = [
                'station_id' => $request->station_id[$key],
                'time' => $request->time[$key],
                'stop_time' => $request->stop_time[$key],
                'money' => $request->money[$key],
            ];
            $train->stops()->create($data);
        }
        
        return redirect('login/train')->with([
            'message' => $id ? '修改成功':'新增成功'
        ]);
    }
    public function delete($id){
        $train = Train::find($id);
        $train->books()->delete();
        $train->stops()->delete();
        $train->delete();
        return redirect('/login/train')->with([
            'message'=> '刪除成功'
        ]);
    }
    public function train(){
        $types = Type::all();
        $stations = Station::all();
        return view('train.train', compact('types', 'stations'));
    }
    public function get($type = '',$day = '',$from = '',$to = ''){
        $from_stop = Station::where('english', $from)->first();
        $to_stop = Station::where('english', $to)->first();
        $trains = Train::where('type_id', $type)->get();
        foreach($trains as $key => $train){
            if(! $train->day($day)){
                $trains->forget($key);
                continue;
            }
            $from_stop = $train->getstop($from_stop->id);
            $to_stop = $train->getstop($to_stop->id);
            if($from_stop == '' || $to_stop == '' || $from_stop >= $to_stop){
                $trains->forget($key);
                continue;
            }
        }
        $types = Type::all();
        $stations = Station::all();
        return view('train.select', compact('types', 'stations','trains'));
    }

    public function see($code = ''){
        $trains = Train::all();
        $data = Train::where('code', $code)->first();
        $stations = Station::all();
        return view('train.see', compact('trains', 'stations', 'data'));
    }
}
