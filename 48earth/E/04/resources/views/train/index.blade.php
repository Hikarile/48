@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{ __('列車管理') }}</div>

<div class="card-body">
    <button class="btn btn-primary" onclick="location.href='{{ url('login/train/add') }}'">新增列車</button>
    
    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>列車代碼</th>
            <th>行車星期</th>
            <th>發車時間</th>
            <th>行經車站</th>
            <th>編輯</th>
        </tr>
        @foreach($trains as $train)
        <tr>
            <td>{{ $train->code }}</td>
            <td>{{ $train->day }}</td>
            <td>{{ $train->start_time }}</td>
            <td>
                @foreach($train->stops as $stop)
                {{ $stations->get($stop->station_id-1)->chinese }},
                @endforeach
            </td>
            <td>
                <button class="btn btn-success" onclick="location.href='{{ url('login/train/fix/'.$train->id) }}'">修改</button>
                <button class="btn btn-danger" onclick="location.href='{{ url('login/train/delete/'.$train->id) }}'">刪除</button>
            </th>
        </tr>
        @endforeach
    </table>
</div>
@endsection

@section('js')
@endsection
