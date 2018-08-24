@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="card">
                <div class="card-header">{{ __('列車管理') }}</div>
                <button class="btn btn-primary" style="width:100px;" onclick="location.href='{{ route('train_add') }}'">新增列車</button>

                <div class="card-body">
                    <table class="table table-hover text-center">
                        <tr class="bg-dark text-light">
                            <th>列車代碼</th>
                            <th>行車星期</th>
                            <th>發車時間</th>
                            <th>行經車站</th>
                            <th>單一車廂載客量</th>
                            <th>車廂數量</th>
                            <th>編輯</th>
                        </tr>
                        @foreach($trains as $train)
                        <tr>
                            <td>{{ $train->name }}</td>
                            <td>{{ $train->day }}</td>
                            <td>{{ $train->start_time }}</td>
                            <td>
                                @foreach($train->stops as $key => $stop)
                                    {{ $stations->get($stop->station_id-1)->chinese }}站
                                    @if($key <  $train->stops()->count()-1)
                                    -><br/>
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $train->people }}</td>
                            <td>{{ $train->number }}</td>
                            <td>
                                <button class="btn btn-success" onclick="location.href='{{ url('login/train/fix/'.$train['id']) }}'">修改</button>
                                <button class="btn btn-danger" onclick="location.href='{{ url('login/train/delete/'.$train['id']) }}'">刪除</button>
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

@section('js')
@endsection