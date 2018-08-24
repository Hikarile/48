@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('車次查詢結果') }}</div>

                <div class="card-body">
                    <table class="table table-hover text-center">
                        <tr class="bg-dark text-light">
                            <th>車種</th>
                            <th>列車編號</th>
                            <th>發車站</th>
                            <th>終點站</th>
                            <th>預計開車時間</th>
                            <th>預計到達時間</th>
                            <th>行駛時間</th>
                            <th>票價</th>
                            <th></th>
                        </tr>
                        <tbody>
                        @foreach($trains as $train)
                        @php
                            $start_time = $train->start_time;
                            
                            $all_time = $train->stops->sum(function ($stop) {
                                return $stop->time + $stop->stop_time;
                            }) - $train->stops->last()->sstop_time;

                            $money = $train->stops->sum(function ($stop) {
                                return $stop->money;
                            });

                            $end_time = date('H:i:s', strtotime($start_time . '+' . $all_time . 'min'));
                        @endphp
                        <tr>
                            <th>{{ $types->name }}</th>
                            <th>{{ $train->name }}</th>
                            <th>{{ $stations->firstWhere('id', $train->stops->first()->station_id)->chinese }}</th>
                            <th>{{ $stations->firstWhere('id', $train->stops->last()->station_id)->chinese }}</th>
                            <th>{{ $start_time }}</th>
                            <th>{{ $end_time }}</th>
                            <th>{{ $all_time }}</th>
                            <th>{{ $money }}</th>
                            <th>
                                <button class="btn btn-success" onclick="location.href='{{ url('booking/' . request()->type . '/' . request()->day . '/' . request()->from . '/' . request()->to) }}'">訂票</button>
                            </th>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $('form').submit(function (event) {
        event.preventDefault();

        var type = $('#type').val();
        var day = $('#day').val();
        var from = $('#from').val();
        var to = $('#to').val();
        location.href = '{{ url("type/select") }}' + '/' + type + '/' + day + '/' + from + '/' + to;
    });
</script>
@endsection