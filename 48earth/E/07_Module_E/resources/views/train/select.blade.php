@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('查詢結果') }}</div>

<div class="card-body">

    <table class="table text-center table-hover">
        <tr class="bg-dark text-light">
            <th>車種</th>
            <th>列車編號</th>
            <th>發車站</th>
            <th>終點站</th>
            <th>預計開出時間</th>
            <th>預計到達時間</th>
            <th>行駛時間</th>
            <th>票價</th>
            <th></th>
        </tr>
        @foreach($trains as $train)
        @php
            $time = 0-$train->stops->last()->stop_time;
            $money = 0;
            foreach($train->stops as $stop){
                $time += $stop->time;
                $time += $stop->stop_time;
                $money += $stop->money;
            }
        @endphp
        <tr>
            <th>{{ $types->get($train->type_id-1)->name }}</th>
            <th>{{ $train->code }}</th>
            <th>{{ $stations->get($train->stops->first()->station_id-1)->chinese }}</th>
            <th>{{ $stations->get($train->stops->last()->station_id-1)->chinese }}</th>
            <th>{{ $train->start_time }}</th>
            <th>{{ date("H:i:s", strtotime(date("Y-m-d").$train->start_time."+".$time."min")) }}</th>
            <th>{{ $time }}</th>
            <th>{{ $money }}</th>
            <th>
                <button class="btn btn-primary" onclick="location.href='{{ url('booking').'/'.$train->code.'/'.request()->day.'/'.request()->from.'/'.request()->to }}'">{{ __('訂票') }}</button>
            </th>
        </tr>
        @endforeach
    </table>
</div>
@endsection
