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

    public function booking($type = '', $day = '', $from = '', $to = ''){
        $stations = Station::all();
        $trains = train::all();
        $types = Type::all();
        return view('booking.booking', compact('stations', 'types', 'trains'));
    }

    public function create(Request $request){
        $train = Train::where('name', $request->name)->first();
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
        foreach ($train->stops->where('id', '>=', $from_stop->id)->where('id', '<', $to_stop->id)->all() as $stop) {
            foreach($books as $key => $book){
                if($book->start == $stop->id){
                    $all += $book->count;
                }if($book->end == $stop->id){
                    $all -= $book->count;
                    $books->forget($key);
                }
            }
            $count = $request->count + $all;
            if ($count > $train->number * $train->people) {
                session()->flash('message', '該區間已無空車位');
                session()->flash('error', 'danger');
                return back();
            }
        }

        $money = 0;
        foreach($train->stops->where('id', '>=', $from_stop->id)->where('id', '<=', $to_stop->id)->all() as $stop){
            $money += $stop->money;
        }

        $time =  date('Y-m-d H:i:s', strtotime($request->day.' '.$train->start_time));
        $tt = $train->stops->where('id', '<=', $to_stop->id)->all();
        foreach($train->stops->where('id', '<=', $to_stop->id)->all() as $key =>  $stop){
            $t = ($stop->time + $stop->stop_time).' min';
            $time = date('Y-m-d H:i:s', strtotime($time." + ".$t));
        }
        
        $data = [
            'code' => $this->token(),
            'phone' => $request->phone,
            'day' => $request->day,
            'start_time' => explode(' ', $time)[1],
            'train_name' => $train->name,
            'start' => $request->from,
            'end' => $request->to,
            'money' => $money,
            'count' => $request->count
        ];
        
        $book = $train->bookings()->create($data);
        return redirect('ok/'.$book->id)->with([
            'message' => '訂票成功',
        ]);
    }

    public function ok($id = ''){
        $booking = Booking::where('id', $id)->firstOrFail();
        $station = Station::get();
        return response()->view('booking.ok', compact('booking', 'station'), 200);
    }
    public function select($phone = ''){
        $booking = Booking::where('phone', $phone)->withTrashed()->get();
        $station = Station::get();
        return response()->view('booking.select', compact('booking', 'station'), 200);
    } 
    public function delete($id){
        Booking::find($id)->delete();
        return back()->with([
            'message' => '訂票取消成功',
        ]);
    } 

    public function log(Request $request){
        $array = [];
        foreach($request->all() as $key => $val){
            $array[] = [$key, $val];
        }

        $booking = Booking::where($array)->paginate(10);
        $stations = Station::all();
        $trains = Train::all();
        return response()->view('booking.log', compact('booking', 'stations', 'trains'), 200);
    }
    
}
