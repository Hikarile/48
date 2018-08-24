@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('列車管理') }}</div>
                <button class="btn btn-primary" style="width:100px;" onclick="location.href='{{ route('train_add_fix') }}'">新增列車</button>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>列車代碼</th>
                            <th>行車星期</th>
                            <th>發車時間</th>
                            <th>行經車站</th>
                            <th>單一車廂在載客數</th>
                            <th>車廂數量</th>
                            <th>編輯</th>
                        </tr>
                        @foreach($train as $val)
                        <tr>
                            <td>{{ $val['name'] }}</td>
                            <td>{{ $val['day'] }}</td>
                            <td>{{ $val['start_time'] }}</td>
                            <td>
                            @foreach($val->stops as $key => $stop)
                                {{ $stations[$stop->station_id-1]->chinese }}
                                @if($key < $val->stops()->count()-1)
                                -><br>
                                @endif
                            @endforeach
                            </td>
                            <td>{{ $val['people'] }}</td>
                            <td>{{ $val['number'] }}</td>
                            <td>
                                <button class="btn btn-success" onclick="location.href='{{ url('login/train/add_fix/'.$val['id']) }}'">修改</button>
                                <button class="btn btn-danger" onclick="location.href='{{ url('login/train/delete/'.$val['id']) }}'">刪除</button>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection