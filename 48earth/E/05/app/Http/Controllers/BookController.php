<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Type;
use App\Station;
use App\Train;
use App\Stop;
use App\Book;

class BookController extends Controller
{
    public function code() {
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 1 , 10));
    }
    public function booking($type = '', $day = '', $from = '', $to = '') {
        $stations = Station::all();
        $trains = Train::all();
        return view('book.booking', compact('stations', 'trains'));
    }
    public function create(Request $request) {
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
        
        $books = $train->books->where('day', $request->day)->all();
        foreach ($train->stops->where('id', '>=', $from_stop->id)->where('id', '<', $to_stop->id) as $stop) {
            $count = $request->count;
            foreach ($books as $book) {
                if ($book->from <= $stop->station_id && $book->to > $stop->station_id) {
                    $count += $book->count;
                    if ($count > $train->car * $train->people) {
                        session()->flash('message', '該區間已無空車位');
                        session()->flash('error', true);
                        return back();
                    }
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
            'code' => $this->code(),
            'phone' => $request->phone,
            'day' => $request->day,
            'time' => $time,
            'from' => $request->from,
            'to' => $request->to,
            'money' => $money,
            'count' => $request->count
        ];
        
        $url = base_path('SMS\\'.$data['phone'].'.text');
        $text = "===================================================================================\r\n列車定位成功。訂票編號:".$data['code']."，".$data['train_name']."車次，".$data['count']."張票，".$data['time']."開，共". number_format($data['money']*$data['count']) ."元。";
        $file = fopen($url,"a+");
		fwrite($file, $text);
        fclose($file);
        
        $book = $train->books()->create($data);
        return redirect('book_ok/'.$book->id)->with([
            'message' => '訂票成功',
        ]);
    }
    public function ok($id = '') {
        $stations = Station::all();
        $books = Book::find($id);
        return view('book.ok', compact('stations', 'books'));
    }

    public function select(Request $request) {
        if($request->phone != '' && $request->code == ''){
            $books = Book::where('phone', $request->phone)->withTrashed()->get();
        }elseif($request->phone == '' && $request->code != ''){
            $books = Book::where('phone', $request->code)->withTrashed()->get();
        }elseif($request->phone != '' && $request->code != ''){
            $books = Book::where('phone', $request->phone)->where('code', $request->code)->withTrashed()->get();
        }else{
            $books = new Book;
        }
        $stations = Station::all();
        return view('book.select', compact('stations', 'books'));
    }

    public function delete($id = '') {
        $books = Book::find($id)->delete();
        return back()->with([
            'message' => '取消成功'
        ]);
    }

    public function index(Request $request) {
        $data = [];
        foreach($request->all() as $key => $val){
            if($key != "_token"){
                $data[$key] = $val;
            }
        }
        $books = Book::where($data)->get();
        $stations = Station::all();
        $trains = Train::all();
        return view('book.index', compact('stations', 'books', 'trains'));
    }
}
