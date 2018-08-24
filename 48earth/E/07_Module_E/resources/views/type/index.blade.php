@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('車種管理') }}</div>

<div class="card-body">
    <button class="btn btn-primary" onclick="location.href='{{ url('login/type/add_fix') }}'">{{ __('新增車種') }}</button>

    <table class="table text-center table-hover">
        <tr class="bg-dark text-light">
            <th>車種名稱</th>
            <th>最高時速</th>
            <th>編輯</th>
        </tr>
        @foreach($types as $type)
        <tr>
            <th>{{ $type->name }}</th>
            <th>{{ $type->speed }}</th>
            <th>
                <button class="btn btn-success" onclick="location.href='{{ url('login/type/add_fix').'/'.$type->id }}'">{{ __('修改') }}</button>
                <button class="btn btn-danger" onclick="location.href='{{ url('login/type/delete').'/'.$type->id }}'">{{ __('刪除') }}</button>
            </th>
        </tr>
        @endforeach
    </table>
</div>
@endsection
