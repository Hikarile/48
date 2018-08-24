@extends('start')
@section('text')
    
    <button onclick="location.href='{{ url('login/station/0') }}'">新增車站</button><p/>
    
    <table width="50%" border="1">
        <tr>
            <td>車站名稱(中文)</td>
            <td>車站名稱(英文)</td>
            <td>設定</td>
        </tr>
        @foreach($data as $val)
        <tr>
            <th>{{ $val['chinese'] }}</th>
            <th>{{ $val['english'] }}</th>
            <th>
                <button onclick="location.href='{{ url('login/station/'.$val['id']) }}'">修改</button>
                <button onclick="location.href='{{ url('login/station_del/'.$val['id']) }}'">刪除</button>
            </th>
        </tr>
        @endforeach
    </table>

@endsection
