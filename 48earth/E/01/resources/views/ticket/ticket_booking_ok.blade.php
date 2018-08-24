@extends('start')
@section('text')

    訂票編號:{{ $booking_id }}<br/>
    手機號碼:{{ $cellphone }}<br/>
    發車時間:{{ $time }}<br/>
    車次:{{ $train_id }}<br/>
    起訖站:{{ $station_s }}/{{ $station_e }}<br/>
    張數:{{ $count }}<br/>
    總票價:{{ $money_all }}<br/>

@endsection
    


