@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{ __('列車管理') }}</div>

<div class="card-body">
    <button class="btn btn-primary" onclick="location.href='{{ url('login/train/add')}}'">{{ __('新增列車') }}</button>

    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>列車代碼</th>
            <th>行車星期</th>
            <th>發車時間</th>
            <th>行經車站</th>
            <th>單一車廂的載客數量</th>
            <th>車廂數量</th>
            <th>總載客數</th>
            <th></th>
        </tr>
        @foreach($trains as $train)
        <tr>
            <th>{{ $train->code }}</th>
            <th>{{ $train->day }}</th>
            <th>{{ $train->start_time }}</th>
            <th>
                @foreach($train->stops as $stop)
                {{ $stations->get($stop->station_id-1)->chinese }},
                @endforeach
            </th>
            <th>{{ $train->people }}</th>
            <th>{{ $train->car }}</th>
            <th>{{ $train->people*$train->car }}</th>
            <th>
                <button class="btn btn-success" onclick="location.href='{{ url('login/train/fix').'/'.$train->id }}'">{{ __('修改') }}</button>
                <button class="btn btn-danger" onclick="location.href='{{ url('login/train/delete').'/'.$train->id }}'">{{ __('刪除') }}</button>
            </th>
        </tr>
        @endforeach
    </table>
</div>
@endsection

@section('js')
@endsection
