@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('車站管理') }}</div>

<div class="card-body">
    <button class="btn btn-primary" onclick="location.href='{{ url('login/station/add_fix') }}'">{{ __('新增車站') }}</button>
    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>車站名稱</th>
            <th>英文名稱</th>
            <th>編輯</th>
        </tr>
        @foreach($stations as $station)
        <tr>
            <th>{{ $station->chinese }}</th>
            <th>{{ $station->english }}</th>
            <th>
            <button class="btn btn-success" onclick="location.href='{{ url('login/station/add_fix').'/'.$station->id }}'">{{ __('修改') }}</button>
            <button class="btn btn-danger" onclick="location.href='{{ url('login/station/delete').'/'.$station->id }}'">{{ __('刪除') }}</button>
            </th>
        </tr>
        @endforeach
    </table>
</div>
@endsection
