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
        $stations = Station::all();
        $trains = Train::all();
        return view('train.index', compact('stations', 'trains'));
    }
    public function add(){
        $stations = Station::all();
        $types = Type::all();
        $days = ['一', '二', '三', '四', '五', '六', '日'];
        return view('train.add', compact('stations', 'types', 'days'));
    }
    public function fix($id = ''){
        $stations = Station::all();
        $types = Type::all();
        $trains = Train::find($id);
        $days = ['一', '二', '三', '四', '五', '六', '日'];
        return view('train.fix', compact('stations', 'types', 'trains', 'days'));
    }
    public function create(Request $request, $id = ''){
        $request->validate([
            'code' => 'required|regex:/^[0-9]+$/|unique:trains,code,'.$id,
        ],[
            'code.unique' => '列車編號不能一樣',
            'code.regex' => '只能為數字',
        ]);

        $data = [
            'type_id' => $request->type_id,
            'code' => $request->code,
            'day' => implode(',', $request->day),
            'start_time' => $request->start_time,
            'people' => $request->people,
            'car' => $request->car,
        ];
        if($id == ''){
            $train = Train::create($data);
        }else{
            $train = Train::find($id);
            $train->update($data);
        }
        $train->stops()->delete();
        $train->books()->delete();
        
        foreach($request->station_id as $key => $val){
            $data = [
                'station_id' => $request->station_id[$key],
                'stop_time' => $request->stop_time[$key],
                'time' => $request->time[$key],
                'money' => $request->money[$key],
            ];
            
            $train->stops()->create($data);
        }

        return redirect('login/train')->with([
            'message' => $id?'修改成功':'新增成功'
        ]);
    }
    public function delete($id = ''){
        Train::find($id)->delete();
        return redirect('login/train')->with([
            'message' => '刪除成功'
        ]);
    }
    public function lop(){
        $stations = Station::all();
        $types = Type::all();
        return view('train.lop', compact('stations', 'types'));
    }
    public function select($type = '', $day = '', $from = '', $to = ''){
        $stations = Station::all();
        $types = Type::all();
        
        $trains = Train::where('type_id', $type)->get();
        foreach($trains as $key => $train){
            if(!$train->day($day)){
                $trains->forget($key);
                continue;
            }
            $from_station = $train->getstop($from);
            $to_station = $train->getstop($to);
            if($from_station == null || $to_station == null || $from_station >= $to_station){
                $trains->forget($key);
                continue;
            }
        }
        
        if(count($trains) == 0){
            session()->flash('message', '查無列車資訊');
            session()->flash('error', 'danger');
        }
        return view('train.select', compact('stations', 'types', 'trains'));
    }
    public function see($code = ''){
        $stations  = Station::all();
        $trains  = Train::all();
        $data = Train::where('code', $code)->first();
        return view('train.see', compact('stations', 'data', 'trains'));
    }
}
