@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">{{ __('車站管理') }}</div>
                <button class="btn btn-primary" style="width:100px;" onclick="location.href='{{ route('station_add_fix') }}'">新增車站</button>

                <div class="card-body">
                    <table class="table table-hover text-center">
                        <tr class="bg-dark text-light">
                            <th>車站名稱</th>
                            <th>英文名稱</th>
                            <th>編輯</th>
                        </tr>
                        @foreach($stations as $station)
                        <tr>
                            <td>{{ $station['chinese'] }}站</td>
                            <td>{{ $station['english'] }}</td>
                            <td>
                                <button class="btn btn-success" onclick="location.href='{{ url('login/station/add_fix/'.$station['id']) }}'">修改</button>
                                <button class="btn btn-danger" onclick="location.href='{{ url('login/station/delete/'.$station['id']) }}'">刪除</button>
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