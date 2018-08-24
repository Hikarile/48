@extends('layouts.app')



@section('content')
<div class="card-header">{{ __('查詢結果') }}</div>

<div class="card-body">
    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>車種</th>
            <th>列車代碼</th>
            <th>發出車站</th>
            <th>終點車站</th>

            <th>預計開車時間</th>
            <th>預計到達時間</th>
            <th>行駛時間</th>
            <th>票價</th>
            <th>編輯</th>
        </tr>
        @foreach($trains as $train)
        <tr>
            <th>{{ $types->get($train->type_id-1)->name }}</th>
            <th>{{ $train->code }}</th>
            <th>{{ $stations->get($train->stops->first()->station_id-1)->chinese }}</th>
            <th>{{ $stations->get($train->stops->last()->station_id-1)->chinese }}</th>
            <th>{{ $train->start_time }}</th>
            @php
            $time = 0-$train->stops->last()->stop_time;
            $money = 0;
            foreach($train->stops as $stop){
                $time += $stop->time;
                $time += $stop->stop_time;
                $money += $stop->money;
            }
            @endphp
            <th>{{ date("H:i:s", strtotime($train->start_time."+".$time."min")) }}</th>
            <th>{{ $time }}</th>
            <th>{{ $money }}</th>
            <th>
                <button class="btn btn-success" onclick="location.href='{{ url('booking').'/'.request()->type.'/'.request()->day.'/'.request()->from.'/'.request()->to }}'">{{ __('訂票') }}</button>
            </th>
        </tr>
        @endforeach
    </table>
</div>
@endsection



