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
    public function code(){
        $all = array_merge(range("A", "Z"), range("a", "z"), range("0", "9"));
        shuffle($all);
        return implode('', array_slice($all, 4 , 10));
    }
    public function booking($code = '', $day = '', $from = '', $to = ''){
        $trains  = Train::all();
        $stations  = Station::all();
        return view('book.index', compact('trains', 'stations'));
    }
    public function create(Request $request){
        $train = Train::where('code', $request->train_name)->first();
        $from_stop = $train->getstop(Station::where('id', $request->from)->first()->english);
        $to_stop = $train->getstop(Station::where('id', $request->to)->first()->english);

        if(!$train->day($request->day)){
            session()->flash('message', 'ˇ當日無該車次列車');
            session()->flash('error', 'danger');
            return bark();
        }
        if(time() > strtotime($request->day . date("H:i:s"))){
            session()->flash('message', '發車時間已過');
            session()->flash('error', 'danger');
            return bark();
        }
        if($from_stop == null || $to_stop == null){
            session()->flash('message', '該列車無行經起訖站');
            session()->flash('error', 'danger');
            return bark();
        }

        $books = $train->books->where('day', $request->day)->all();
        foreach($train->stops->where('id', '<=', $from_stop->id)->where('id', '>', $to_stop->id)->all() as $stop){
            $all =  $request->count;
            foreach($books as $book){
                if($book->from <= $stop->station_id && $book->to > $stop->station_id){
                    $all += $book->count;
                    if($all > $train->car*$train->people){
                        session()->flash('message', '該區間已無空車位');
                        session()->flash('error', 'danger');
                        return bark();
                    }
                }
            }
        }

        $time = 0-$to_stop->stop_time;
        $money = 0;
        foreach($train->stops as $stop){
            if($stop->id>= $from_stop->id && $stop->id<= $to_stop->id){
                $time += $stop->time;
                $time += $stop->stop_time;
                $money += $stop->money;
            }
        }

        $data = [
            'train_name' => $request->train_name,
            'phone' => $request->phone,
            'code' => $this->code(),
            'day' => $request->day,
            'time' => date("H:i:s", strtotime($request->day.$train->start_time."+".$time."min")),
            'from' => $request->from,
            'to' => $request->to,
            'count' => $request->count,
            'money' => $money,
        ];
        $book = $train->books()->create($data);

        $url = base_path('SMS\\'.$book->phone.'.text');
        $text = "========================================\r\n列車訂位成功。訂票編號:".$book->code."，".date("m/d", strtotime($book->day.$book->time)).Station::where('id', $request->from)->first()->chinese.Station::where('id', $request->to)->first()->chinese.$book->train_name."車次，".$book->count."張票，".date("H:i", strtotime($book->day.$book->time))."開，共". number_format($book->count*$book->money)."元\r\n";
        $file = fopen($url, "a+");
        fwrite($file, $text);
        fclose($file);

        return redirect('book/ok/'.$book->id)->with([
            'message' => '新增成功'
        ]);
    }
    public function ok($id = ''){
        $books = Book::find($id);
        $stations  = Station::all();
        return view('book.ok', compact('books', 'stations'));
    }
    public function select(Request $request){
        $stations  = Station::all();
        $tf = false;

        if($request->phone != null && $request->code != null){
            $tf = true;
            $books = Book::where('phone', $request->phone)->where('code', $request->code)->get();
        }elseif($request->phone != null && $request->code == null){
            $tf = true;
            $books = Book::where('phone', $request->phone)->get();
        }elseif($request->phone == null && $request->code != null){
            $tf = true;
            $books = Book::where('code', $request->code)->get();
        }

        if($tf){
            return view('book.select', compact('books', 'stations', 'tf'));
        }else{
            return view('book.select', compact('stations', 'tf'));
        }

    }
    public function delete($id = ''){
        Book::find($id)->delete();
        return back()->with([
            'message' => '訂票取消成功',
        ]);
    }
    public function index(Request $request){
        $data = [];
        foreach($request->all() as $key => $val){
            if($key != "_token" && $key != "page"){
                $data[$key] = $val;
            }
        }
        $books = Book::where($data)->withTrashed()->paginate(10);
        $stations = Station::all();
        $trains = Train::all();
        return view('book.login', compact('books', 'stations', 'trains'));
    }
}
