@extends('start')
@section('text')
    <button onclick="location.href='{{ route('train_add') }}'">新增</button><p/>

    <table width="80%" border="1">
        <tr>
            <th>列車代碼</th>
            <th>行車星期</th>
            <th>行經車站</th>
            <th>單一車廂載客數</th>
            <th>車廂數量</th>
            <th>總載客數</th>
            <th>編輯</th>
        </tr>
        @foreach($data as $val)
        <tr>
            <th>{{ $val['train_name'] }}</th>
            <th>{{ $val['day'] }}</th>
            <th>
                @foreach($val['stop'] as $key => $val2)
                    {{ $val2 }}<br/>
                @endforeach
            </th>
            <th>{{ $val['car_people'] }}</th>
            <th>{{ $val['car_count'] }}</th>
            <th>{{ $val['car_all'] }}</th>
            <th>
                <button onclick="location.href='{{ url('login/train_fix/'.$val['id']) }}'">修改</button>
                <button onclick="location.href='{{ url('login/train_del/'.$val['id']) }}'">刪除</button>
            </th>
        </tr>
        @endforeach
    </table>

@endsection
    


