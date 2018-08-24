<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Type;
use App\Model\Station;
use App\Model\Train;
use App\Model\Stop;
use App\Model\Booking;

class TrainController extends Controller
{
    
    public function train_select(Request $request){ //首頁-列車查詢
        $data = ['station' => [], 'type' => []];

        $station = Station::all();
        foreach($station as $val){
            $data['station'][] = [
                'chinese' => $val->chinese,
                'english' => $val->english
            ];
        }
        $type = Type::all();
        foreach($type as $val){
            $data['type'][] = [
                'id' => $val->id,
                'type' => $val->type
            ];
        }
        
        return response()->view('train.train_select.train_select', $data, 200);
    }
    public function train_select_create($station_s = '', $station_e = '', $type_id = '', $day = ''){//首頁-列車查詢-結果
        if($station_e == $station_s){
            session()->flash('error', '發車站跟終點站不能一樣');
            return back();
        }
        $week = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        $wek = $week[date('w', strtotime($day))];

        $s = Station::where('english', $station_s)->firstOrFail();
        $e = Station::where('english', $station_e)->firstOrFail();
        
        $data = [
            's' => $station_s,
            'e' => $station_e,
            's_c' => $s->chinese,
            'e_c' => $e->chinese,
            'day' => $day,
            'train' => []
        ];

        $train = Train::with('stops')->where($wek, '1')->where('type_id', $type_id)->get();

        foreach($train as $val1){
            $s_time = $day.' '.$val1->station_s_time; 
            $e_time = $day.' '.$val1->station_s_time;
            $all_time = 0;
            $all_money = 0;
            $ss = false;
            $ee = false;

            $stop = Stop::where('train_id', $val1->id)->get();
            foreach($stop as $val2){
                if($val2->station_e == $station_s){
                    $ss = true;
                }if($val2->station_e == $station_e && $ss){
                    $e_time = date('Y-m-d h:i:s', strtotime($e_time ."+ ".$val2->time." minute"));
                    $all_time += $val2->time;
                    $all_money += $val2->moeny;
                    $ee = true;
                    break;
                }if($ss){
                    $e_time = date('Y-m-d h:i:s', strtotime($e_time ."+ ".$val2->time." minute"));
                    $all_time += $val2->time;
                    $all_money += $val2->moeny;
                }else{
                    $s_time = date('Y-m-d h:i:s', strtotime($s_time ."+ ".$val2->time." minute"));
                    $e_time = date('Y-m-d h:i:s', strtotime($e_time ."+ ".$val2->time." minute"));
                }
            }

            if($ss && $ee){
                $type = Type::where('id', $type_id)->firstOrFail();
                $data['train'][] = [
                    'type' => $type->type,
                    'train_name' => $val1->train_name,
                    's_e' => $val1->station_s.'/'.$val1->station_e,
                    's_time' => date('h:i', strtotime($s_time)),
                    'e_time' => date('h:i', strtotime($e_time)),
                    'all_time' => $all_time,
                    'all_money' => $all_money,
                ];
            }
        }

        if(empty($data['train'])){
            session()->flash('error', '查無指定條件的車次，請更換條件再查詢');
        }
        return response()->view('train.train_select.train_select_create', $data, 200);
    }


    public function train_data($train_name = ''){ //列車資訊
        $data = [
            'id' => '',
            'train_name' => $train_name,
            'day' => "",
            'station' => ""
        ];
        if($train_name != ""){
            $train = Train::where('train_name' , $train_name)->first();
            if($train == "null"){
                session()->flash('errors', '查無列車資訊');
            }else{
                $week = ["mon" => '一', "tue" => '二', "wed" => '三', "thu" => '四', "fri" => '五', "sat" => '六', "sun" => '日'];
                foreach($week as $key => $val){
                    if($train->$key == 1){
                        if(!isset($day)){
                            $day = $val;
                        }else{
                            $day = $day.','.$val;
                        }
                    }
                }
                
                $station = [];
                $stime = date("Y/m/d h:i:s", strtotime(date("Y/m/d")." ".$train->station_s_time));
                $etime = "";

                $stop = Stop::where('train_id', $train->id)->get();
                foreach($stop as $key => $val){
                    if($key > 0){
                        $etime = date("Y/m/d h:i:s", strtotime($stime." + ".$val->time." minute"));
                        $stime = date("Y/m/d h:i:s", strtotime($etime." + ".$val->stop_time." minute"));
                    }
                    $station[$key] = [
                        'station' => $val->station_s,
                        's_time' => $key == count($stop)-1 ? '' :  date("h:i:s", strtotime($stime)), //發車
                        'e_time' => $key == 0 ? '' :  date("h:i:s", strtotime($etime))  //抵達
                    ];
                }
                $data['day'] = $day;
                $data['station'] = $station;
            }
        }
        
        return response()->view('train.train_data', $data, 200);
    }
    public function train_booking($train_name =''){//列車資訊->訂票
        $data = [
            'train_name' => $train_name,
            'station' => []
        ];

        $station = Station::all();
        foreach($station as $val){
            $data['station'][] = [
                'chinese' => $val->chinese,
                'english' => $val->english
            ];
        }
        return response()->view('ticket.train_booking', $data, 200);
    }

    public function type(){ //車種管理
        $type = Type::all();
        $data['data'] = [];
        foreach($type as $val){
            $data['data'][] = [
                'id' => $val->id,
                'type' => $val->type,
                'speed' => $val->speed,
            ];
        }
        return response()->view('train.type.type', $data, 200);
    }
    public function type_id($id = ''){//新增,修改頁面
        if($id == "0"){
            $data = [
                'tf' => true,
            ];
        }else{
            $type = Type::where('id', $id)->firstOrFail();
            $data = [
                'id' => $type->id,
                'type' => $type->type,
                'speed' => $type->speed,
                'tf' => false,
            ];
        }
        return response()->view('train.type.type_id', $data, 200);
    }
    public function type_create(Request $request){ //新增,修改資料
        $rel = [
            'type' => 'required',
            'speed' => 'required|numeric'
        ];
        $message = [
            'type.required' => '未填寫完成',
            'speed.required' => '未填寫完成',
            'speed.numeric' => '填寫錯誤',
        ];
        $request->validate($rel, $message);

        if($type = Type::find($request->id)) {
            $type->update($request->all());
            session()->flash('message', '修改車種成功');
            return redirect('login\type');
        }

        $rel = ['type' => 'unique:types',];
        $message = ['type.unique' => '此名稱已被使用',];
        $request->validate($rel, $message);

        Type::create($request->all());
        session()->flash('message', '新增車種成功');
        return redirect('login\type');
    }
    public function type_del($id){//刪除車種
        $type = Type::find($id)->first();
        $trains = Train::where('type_id', $type->id)->get();
        foreach($trains as $train){
            $stop = Stop::where('train_id', $train->id)->get();
            $booking = Booking::where('train_id', $train->train_name)->get();
            if(empty($stop)){
                $stop->delete();
            }if(empty($booking)){
                $booking->delete();
            }
            $train->delete();
        }
        $type->delete();
        session()->flash('message', '刪除成功');
        return redirect('login\type');
    }


    public function station(){ //車站管理
        $station = Station::all();
        $data['data'] = [];
        foreach($station as $val){
            $data['data'][] = [
                'id' => $val->id,
                'chinese' => $val->chinese,
                'english' => $val->english,
            ];
        }
        return response()->view('train.station.station', $data, 200);
    }
    public function station_id($id = ''){//新增,修改頁面
        if($id == "0"){
            $data = [
                'tf' => true
            ];
        }else{
            $station = Station::where('id', $id)->firstOrFail();
            $data = [
                'id' => $id,
                'chinese' => $station->chinese,
                'english' => $station->english,
                'tf' => false
            ];
        }
        return response()->view('train.station.station_id', $data, 200);
    }
    public function station_create(Request $request){ ///新增,修改資料
        $rel = [
            'chinese' => 'required',
            'english' => 'required'
        ];
        $message = [
            'chinese.required' => '未填寫完成',
            'english.required' => '未填寫完成'
        ];
        $request->validate($rel, $message);

        if($station = Station::find($request->id)){
            $station->update($request->all());
            session()->flash('message', '修改成功');
            return redirect('login/station');
        }

        $rel = [
            'chinese' => 'unique:stations',
            'english' => 'unique:stations'
        ];
        $message = [
            'chinese.unique' => '此中文名稱已被使用',
            'english.unique' => '此英文名稱已被使用'
        ];
        $request->validate($rel, $message);

        Station::create($request->all());
        session()->flash('message', '新增成功');
        return redirect('login/station');
    }
    public function station_del($id){//刪除車站
        $station = Station::find($id);
        $stop = Stop::all();

        $tf = true;
        foreach($stop as $val){
            if($val->station_s == $station->chinese){
                $tf = false;
                break;
            }
        }

        if($tf){
            $station->delete();
            session()->flash('message', '刪除成功');
        }else{
            session()->flash('message', '無法刪除');
        }
        return redirect('login/station');
    }

    public function train(){ //列車管理
        $train = Train::all();
        $data['data'] = [];
        
        foreach($train as $val1){
            $day = ""; $stop = [];

            $week = ['日' => $val1->sun, '一' => $val1->mon, '二' => $val1->tue, '三' => $val1->wed, '四' => $val1->thu, '五' => $val1->fri, '六' => $val1->sat];
            foreach($week as $key => $val2){
                if($val2 == 1){
                    if($day == ""){
                        $day = $key;
                    }else{
                        $day = $day.','.$key;
                    }
                }
            }
            
            $stop_model = Stop::where('train_id', $val1->id)->get();
            foreach($stop_model as $key => $val3){
                $stop[] = $val3->station_s;
            }
            
            $data['data'][] = [
                'id' => $val1->id,
                'train_name' => $val1->train_name,
                'day' => $day,
                'stop' => $stop,
                'car_people' => $val1->car_people,
                'car_count' => $val1->car_count,
                'car_all' => $val1->car_all
            ];
        }
        return response()->view('train.train.train', $data, 200);
    }
    public function train_add(){ //新增頁面
        $data = [];

        $type = Type::all();
        foreach($type as $val){
            $data['type'][] = [
                'id' => $val->id,
                'type' => $val->type,
            ];
        }

        $station = Station::all();
        foreach($station as $val){
            $data['station'][] = [
                'id' => $val->id,
                'chinese' => $val->chinese,
                'english' => $val->english,
            ];
        }
        
        return response()->view('train.train.train_add', $data, 200);
    }
    public function train_fix($id){ //修改頁面
        $data = [];
        
        $type = Type::all();
        foreach($type as $val){
            $data['type'][] = [
                'id' => $val->id,
                'type' => $val->type,
            ];
        }

        $station = Station::all();
        foreach($station as $val){
            $data['station'][] = [
                'id' => $val->id,
                'chinese' => $val->chinese,
                'english' => $val->english,
            ];
        }

        $train = Train::where('id', $id)->first();
        $st = Stop::where('train_id', $train->id)->get();
        foreach($st as $val){
            $stop[] = [
                'station_s' => $val->station_s,
                'station_e' =>  $val->station_e,
                'stop_time' => $val->stop_time,
                'time' => $val->time,
                'moeny' => $val->moeny
            ];
        }
        $data['train'] = [
            'id' => $train->id,
            'type_id' => $train->type_id,
            'train_name' => $train->train_name,
            'car_count' => $train->car_count,
            'car_people' =>  $train->car_people,
            'car_all' =>  $train->car_all,
            'mon' =>  $train->mon,
            'tue' =>  $train->tue,
            'wed' =>  $train->wed,
            'thu' =>  $train->thu,
            'fri' =>  $train->fri,
            'sat' =>  $train->sat,
            'sun' =>  $train->sun,
            'station_s' => substr($train->station_s , 0 , 5 ),
            'station_s_time' =>  $train->station_s_time,
            'station_e' =>  $train->station_e,
            'stop' => $stop
        ];

        return response()->view('train.train.train_fix', $data, 200);
    }
    public function train_create(Request $request){ //新增,修改資料
        $train_data = [
            'type_id' => $request->type_id,
            'train_name' => $request->train_name,
            'car_count' => $request->car_count,
            'car_people' => $request->car_people,
            'car_all' => $request->car_all,
            'mon' => $request->mon == null ? 0 : 1,
            'tue' => $request->tue == null ? 0 : 1,
            'wed' => $request->wed == null ? 0 : 1,
            'thu' => $request->thu == null ? 0 : 1,
            'fri' => $request->fri == null ? 0 : 1,
            'sat' => $request->sat == null ? 0 : 1,
            'sun' => $request->sun == null ? 0 : 1,
            'station_s' => $request->station_s,
            'station_s_time' => $request->station_s_time.":00",
            'station_e' => $request->station_e
        ];
        if($request->ididid == null){
            $train = Train::create($train_data);
        }else{
            $train = Train::where('id', $request->ididid)->update($train_data);
            Stop::where('train_id', $request->ididid)->delete();
        }

        for($i = 0 ; $i <= $request->count ; $i++){
            $stop = [
                'train_id' => $train->id,
                'station_s' => $request->station_c[$i],
                'station_e' =>  $request->station_c[$i],
                'stop_time' => $request->stop_time[$i],
                'time' => $request->time[$i],
                'moeny' => $request->money[$i]
            ];
            Stop::create($stop);
        }

        session()->flash('message', '修改成功');
        return redirect('login/train');
    }
    public function train_del($id){ //刪除列車
        $train = Train::where('id', $id)->first();
        Stop::where('train_id', $id)->delete();
        Booking::where('train_id', $train->train_name)->delete();
        $train->delete();

        session()->flash('message', '刪除成功');
        return redirect('login/train');
    }
}
