@extends('start')
@section('text')
    
    <form action="{{ route('booking_create') }}" method="post">
        {{ csrf_field() }}
        手機號碼:<input name="cellphone" id="cellphone"><br/>
        起程站:
        <select name="station_s" id="station_s">
            @foreach($station as $val)
                <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
            @endforeach
        </select><br/>
        到達站:
        <select name="station_e" id="station_e">
            @foreach($station as $val)
                <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
            @endforeach
        </select><br/>
        搭乘日期:<input type="date" name="day" id="day" value=""><br/>
        車次代碼:<input type="number" name="train_id" id="train_id" value="{{ $train_name }}"><br/>
        車票張數:<input type="number" name="count" id="count" value="1"><br/>
        <button>確定</button>
    </form>

@endsection
    


