<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Booking;

class BookingController extends Controller
{
    public function token(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 1, 10));
    }

    public function index(Request $request){
        $stations =  Station::all();
        $trains =  Train::all();
        return view('book.index', compact('trains', 'stations'));
    }

    public function create(Request $request){
        $request->validate([
            'phone'  => 'required|regex:/^[0-9]+$/'
        ],[
            'phone.regex' => '手機格式輸入錯誤'
        ]);
        
        $train = Train::where('code', $request->code)->first();
        $from_stop = $train->getstop($request->from);
        $to_stop = $train->getstop($request->to);
        
        if ($train == '' || !$train->day($request->day) ) {
            session()->flash('message', '當日無該車次列車');
            session()->flash('error', 'danger');
            return back();
        }
        if (time() >= strtotime($request->day . $train->start_time)) {
            session()->flash('message', '發車時間已過');
            session()->flash('error', 'danger');
            return back();
        }
        if ($from_stop == '' || $to_stop == '' || $from_stop->id >= $to_stop->id) {
            session()->flash('message', '該列車無行經起訖站');
            session()->flash('error', 'danger');
            return back();
        }
        
        $books = $train->bookings->where('day', $request->day)->all();
        $all = 0;
        foreach($train->stops->where('id', '>=', $from_stop->id)->where('id', '<', $to_stop->id)->all() as $stop){
            foreach($books as $book){
                if($book->from == $stop->id){
                    $all += $book->count;
                }if($book->to == $stop->id){
                    $all -= $book->count;
                }
                $count = $request->count + $all;
                if ($count > $train->number * $train->people) {
                    session()->flash('message', '該區間已無空車位');
                    session()->flash('error', 'danger');
                    return back();
                }
            }
        }
        
        $money = 0;
        foreach($train->stops->where('id', '>=', $from_stop->id)->where('id', '<=', $to_stop->id)->all() as $stop){
            $money += $stop->money;
        }
        
        $time = 0-$to_stop->stop_time;
        foreach($train->stops->where('id', '<=', $from_stop->id)->all() as $stop){
            $time += $stop->time;
            $time += $stop->stop_time;
        }
        $time =  date('H:i:s', strtotime($request->day.' '.$train->start_time ."+". $time."min"));

        $data = [
            'train_name' => $request->code,
            'code' => $this->token(),
            'phone' => $request->phone,
            'day' => $request->day,
            'time' => $time,
            'from' => $request->from,
            'to' => $request->to,
            'money' => $money,
            'count' => $request->count
        ];

        $url = base_path('SMS\\'.$data['phone'].'.text');
        $text = "===================================================================================\r\n列車定位成功。訂票編號:".$data['code']."，".$data['train_name']."車次，".$book['count']."張票，".$data['time']."開，共".$data['money']*$data['count']."元。";
        $file = fopen($url,"a+");
		fwrite($file, $text);
        fclose($file);

        $book = $train->bookings()->create($data);
        return redirect('ok/'.$book->id)->with([
            'message' => '訂票成功',
        ]);
    }

    public function ok($id = ''){
        $booking = Booking::where('id', $id)->firstOrFail();
        $station = Station::get();
        return response()->view('book.ok', compact('booking', 'station'), 200);
    }

    public function select($phone = null, $code = null){
        $bookings = Booking::where('phone', $phone)->orWhere('code', $code)->withTrashed()->get();
        $stations = Station::all();
        return view('book.select', compact('bookings', 'stations'));
    }

    public function delete($id = ''){
        $booking = Booking::find($id)->delete();
        return back()->with([
            'message' => '刪除成功'
        ]);
    }

    public function book(Request $request){
        $array = [];
        foreach($request->all() as $key => $val){
            $array[] = [$key, $val];
        }
        
        $bookings = Booking::where($array)->paginate(10);
        $stations = Station::all();
        $trains = Train::all();

        return view('book.book', compact('bookings', 'stations', 'trains'));
    }
}
