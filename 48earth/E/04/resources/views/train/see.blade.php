@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{  __('查詢節果') }}</div>

<div class="card-body">
    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>車種</th>
            <th>列車邊號</th>
            <th>發車站</th>
            <th>終點站</th>
            <th>預計開車時間</th>
            <th>預計到達時間</th>
            <th>行駛時間</th>
            <th>票價</th>
            <th></th>
        </tr>
        @foreach($trains as $train)
        <tr>
            @php
                $all_time = $train->stops->sum(function($stop){
                    return $stop->time + $stop->stop_time;
                });
                $money = $train->stops->sum(function($stop){
                    return $stop->money;
                });
                $end_time = date("H:i:s", strtotime($train->start_time. "+" .$all_time."min"));
            @endphp
            <th>{{ $types->name }}</th>
            <th>{{ $train->code }}</th>
            <th>{{ $stations->firstWhere('id', $train->stops->first()->station_id)->chinese }}</th>
            <th>{{ $stations->firstWhere('id', $train->stops->last()->station_id)->chinese }}</th>
            <th>{{ $train->start_time }}</th>
            <th>{{ $end_time }}</th>
            <th>{{ $all_time }}分鐘</th>
            <th>{{ $money }}元</th>
            <th>
                <button class="btn btn-success" onclick="location.href='{{ url('booking') }}/{{ request()->type }}/{{ request()->day }}/{{ request()->from }}/{{ request()->to }}'">訂票</button>
            </th>
        </tr>
        @endforeach
    </table>
</div>
@endsection
