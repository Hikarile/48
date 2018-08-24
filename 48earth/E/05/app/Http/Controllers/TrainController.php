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
        $trains = Train::all();
        $days = ['一','二','三','四','五','六','日'];
        return view('train.add', compact('stations', 'trains', 'days', 'types'));
    }
    public function fix($id = ''){
        $train = Train::find($id);
        $stations = Station::all();
        $types = Type::all();
        $trains = Train::all();
        $days = ['一','二','三','四','五','六','日'];
        return view('train.fix', compact('stations', 'trains', 'days', 'types', 'train'));
    }
    public function create(Request $request, $id= ''){
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
            'message' => $id?'修改成功':'新增成功'
        ]);
    }
    public function delete($id= ''){
        $train = Train::find($id);
        $train->stops()->delete();
        $train->books()->delete();
        $train->delete();;
        return redirect('login/train')->with([
            'message' => '刪除成功'
        ]);
    }
    public function select(){
        $stations = Station::all();
        $types = Type::all();
        return view('train.select', compact('stations', 'types'));
    }
    public function see($type = '', $day = '', $from = '', $to = '') {

        $from  = Station::where('english', $from)->first();
        $to  = Station::where('english', $to)->first();
        $trains = Train::where('type_id', $type)->get();

        foreach($trains as $key => $train){
            if(!$train->day($day)){
                $trains->forget($key);
                continue;
            }

            $from_stop = $train->getstop($from->id);
            $to_stop = $train->getstop($to->id);
            if($from_stop=='' || $to_stop == '' || $from_stop->id >= $to_stop->id){
                $trains->forget($key);
                continue;
            }
        }

        $stations = Station::all();
        $types = Type::all();
        return view('train.see', compact('stations', 'types', 'trains'));
    }

    public function log($code= ''){
        $stations = Station::all();
        $trains = Train::all();
        $datas = Train::where('code', $code)->first()?:new Train;
        return view('train.log', compact('trains', 'datas', 'stations'));
    }

}
