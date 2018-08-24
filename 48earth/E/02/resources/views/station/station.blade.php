@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('車站管理') }}</div>
                <button class="btn btn-primary" style="width:100px;" onclick="location.href='{{ route('station_add_fix') }}'">新增車站</button>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>車站名稱(中文)</th>
                            <th>車站名稱(英文)</th>
                            <th>編輯</th>
                        </tr>
                        @foreach($station as $val)
                        <tr>
                            <td>{{ $val['chinese'] }}</td>
                            <td>{{ $val['english'] }}</td>
                            <td>
                                <button class="btn btn-success" onclick="location.href='{{ url('login/station/add_fix/'.$val['id']) }}'">修改</button>
                                <button class="btn btn-danger" onclick="location.href='{{ url('login/station/delete/'.$val['id']) }}'">刪除</button>
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

