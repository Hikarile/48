@extends('start')
@section('text')
    <button onclick="location.href='{{ url('login/type_id/0') }}'">新增車種</button><p/>

    <table width="50%" border="1" id="table">
        <tr>
            <th>車種</th>
            <th>時速(km/hr)</th>
            <th>編輯</th>
        </tr>
        @foreach($data as $val)
            <tr>
                <td>{{ $val['type'] }}</td>
                <td>{{ $val['speed'] }}</td>
                <td>
                    <button onclick="location.href='{{ url('login/type_id/'.$val['id']) }}'">修改</button>
                    <button onclick="location.href='{{ url('login/type_del/'.$val['id']) }}'">刪除</button>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
    


