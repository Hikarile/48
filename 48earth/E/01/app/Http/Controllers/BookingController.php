<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\Type;
use App\Model\Station;
use App\Model\Train;
use App\Model\Stop;
use App\Model\Booking;
use App\Model\Verification;
use File;

class BookingController extends Controller
{

    private function token(){
        $all  = array_merge(range("A","Z"),range("a","z"),range("0","9"));
        shuffle($all);
        return  implode('', array_slice($all, 0, 10));
    }

    
    public function ticket_booking($s='', $e='', $day='', $train_type=''){ //預訂車票
        $data = [
            's' => $s,
            'e' => $e,
            'day' => $day,
            'train_type' => $train_type,
            'station' => [],
            'verification', [],
        ];

        $station = Station::all();
        foreach($station as $val){
            $data['station'][] = [
                'id' => $val->id,
                'chinese' => $val->chinese,
                'english' => $val->english
            ];
        }
        $verification = Verification::all();
        foreach($verification as $val){
            $data['verification'][] = [
                'url' => $val->url,
                'da' => $val->da,
                'text' => $val->text,
            ];
        }
        
        return response()->view('ticket.ticket_booking', $data, 200);
    }
    public function booking_create(Request $request){ //新增資料
        $rel = [
            'cellphone' => 'required',
            'day' => 'required',
            'train_id' => 'required',
            'count' => 'required|min:1'
        ];
        $message = [
            'cellphone.required' => '手機號碼未填寫',
            'day.required' => '搭乘日期未填寫',
            'train_id.required' => '車次代碼未填寫',
            'count.required' => '車票張數未填寫',
            'count.min' => '訂票張數不能小於1'
        ];
        $request->validate($rel, $message);

        $week = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
        $week = $week[date('w', strtotime($request->day))];
        
        $train = Train::where('train_name', $request->train_id)->where($week, '1')->firstOrFail();
        $stop = Stop::where('train_id', $train->id)->get();
        $booking = Booking::where('train_id', $request->train_id)->get();
        
        if(strtotime($request->day.' '.$train->station_s_time) < time()){
            session()->flash('error', '發車時間已過');
            return back();
        }else if($request->station_s == $request->station_e){
            session()->flash('error', '發車站跟終點站不能一樣');
            return back();
        }

        $stop_s = false; $stop_e = false; $all_money = 0;
        foreach($stop as $val){
            if($stop_s){
                $all_money += $val->moeny;
            }if($request->station_s == $val->station_e){
                $stop_s = true;
            }else if($request->station_e == $val->station_e && $stop_s){
                $stop_e = true;
                break;
            }
        }if(!$stop_s || !$stop_e){
            session()->flash('error', '該列車無行經起訖站');
            return back();
        }
        
        $ticket_all = 0;
        $s = Station::where('english', $request->station_s)->firstOrFail();
        $e = Station::where('english', $request->station_e)->firstOrFail();
        foreach($booking as $val1){
            $ss = false; $tf = false;
            foreach($stop as $val2){
                if($val1->station_s == $val2->station_s){
                    $ss = true;
                }if($val1->station_e == $val2->station_s){
                    break;
                }if($ss && $val2->station_e == $request->station_s){
                    $tf = true; break;
                }
            }
            if($tf){
                $ticket_all += $val1->count;
            }
        }
        if(($train->car_all - $ticket_all) < (int)$request->count) {
            session()->flash('error', '該區間已無空車位');
            return back();
        }
        
        $data = [
           'train_id' => $request->train_id,
           'booking_id' => $this->token(),
           'cellphone' => $request->cellphone,
           'booking_day' => date("Y-m-d"),
           'start_day' =>  $request->day,
           'time' => $train->station_s_time,
           'station_s' => $s->chinese,
           'station_e' => $e->chinese,
           'day' => $request->day,
           'count' => $request->count,
           'money_one' => $all_money,
           'money_all' => $all_money*$request->count,
           'del' => 0,
        ];

        $book = Booking::create($data);
        session()->flash('message', '訂票成功');
        
        $url = base_path('public\SMS\\'.$request->cellphone.'.text');
        $text = "===================================================================================\r\n列車定位成功。訂票編號:".$book->booking_id."，".$book->start_day.$book->station_s.$book->station_e.$book->train_id."車次，".$book->count."張票，".$book->time."開，共".$book->money_all."元。";
       
        $file = fopen($url,"a+");
		fwrite($file, $text);
        fclose($file);
        
        return response()->view('ticket.ticket_booking_ok', $data, 200);
    }


    public function ticket_select($booking_id = '' , $cellphone = ''){ //訂票查詢
        $data = [
            'tf' => false,
            'cellphone' => $cellphone != '_' ? $cellphone : '',
            'booking_id' => $booking_id != '_' ? $booking_id : '',
            'booking' => []
        ];

        $tf = false;
        if($booking_id != "" && $cellphone != ""){
            $tf = true;
        }
        
        if($cellphone != "_" &&  $booking_id != "_" && $tf){
            $booking = Booking::where('cellphone', $cellphone)->where('booking_id', $booking_id)->get();
        }else if($cellphone != "_" &&  $booking_id == "_" && $tf){
            $booking = Booking::where('cellphone', $cellphone)->get();
        }else if($cellphone == "_" &&  $booking_id != "_" && $tf){
            $booking = Booking::where('booking_id', $booking_id)->get();
        }
        
        if($tf){
            $data['tf'] = true;
            foreach($booking as $val){
                $data['booking'][] = [
                    'id' => $val->id,
                    'booking_id' => $val->booking_id,
                    'booking_day' => $val->booking_day,
                    'start_day' => $val->start_day,
                    'train_id' => $val->train_id,
                    'station' => $val->station_s.'/'.$val->station_e,
                    'count' => $val->count,
                    'del' => $val->del
                ];
            }
        }
        
        return response()->view('ticket.ticket_select', $data, 200);
    }
    public function ticket_del($id = "") { //取消訂票
        $book = Booking::where('id', $id)->first();
        $book->del = 1;
        $book->save();
        return back();
    }


    public function ticket($train_name = "", $day = "", $cellphone = "", $booking_id = "", $station_s = "", $station_e = ""){ //訂票紀錄查詢
        $data = [
            'train_name' => $train_name,
            'day' => $day,
            'cellphone' => $cellphone,
            'booking_id' => $booking_id,
            'station_s' => $station_s,
            'station_e' => $station_e,
            'station' => [],
            'booking'=> []
        ];

        $station = Station::all();
        foreach($station as $val){
            $data['station'][] = [
                'id' => $val->id,
                'chinese' => $val->chinese,
                'english' => $val->english
            ];
        }

        if($train_name != "" && $day != "" && $cellphone != "" && $booking_id != "" && $station_s != "" && $station_e != ""){
            $booking = Booking::where('train_id', $train_name)
                                ->where('start_day', $day)
                                ->where('cellphone', $cellphone)
                                ->where('booking_id', $booking_id)
                                ->where('station_s', $station_s)
                                ->where('station_e', $station_e)->get();
            
        }else{
            $booking = Booking::all();
        }
         
        foreach($booking as $val){
            $data['booking'][] = [
                'id' => $val->id,
                'booking_day' => $val->booking_day,
                'time' => $val->time,
                'train_id' => $val->train_id,
                'station' => $val->station_s.'/'.$val->station_e,
                'booking_day' => $val->booking_day,
                'count' => $val->count,
                'del' => $val->del,
            ];
        }

        return response()->view('ticket.ticket', $data, 200);
    }

}
