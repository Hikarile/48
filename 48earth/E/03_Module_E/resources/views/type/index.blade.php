@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <div class="card">
                <div class="card-header">{{ __('車種管理') }}</div>
                <button class="btn btn-primary" style="width:100px;" onclick="location.href='{{ route('type_add_fix') }}'">新增車種</button>

                <div class="card-body">
                    <table class="table table-hover text-center">
                            <tr class="bg-dark text-light">
                            <th>車種名稱</th>
                            <th>最高時速</th>
                            <th>編輯</th>
                        </tr>
                        @foreach($types as $type)
                        <tr>
                            <td>{{ $type['name'] }}</td>
                            <td>{{ $type['speed'] }}</td>
                            <td>
                                <button class="btn btn-success" onclick="location.href='{{ url('login/type/add_fix/'.$type['id']) }}'">修改</button>
                                <button class="btn btn-danger" onclick="location.href='{{ url('login/type/delete/'.$type['id']) }}'">刪除</button>
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