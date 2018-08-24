@extends('start')
@section('text')
    <script>
        $(function(){
            $(".booking").click(function(){
                var s = $(this).attr('s');
                var e = $(this).attr('e');
                var day = $(this).attr('day');
                var train_name = $(this).attr('train_name');
                location.href = "{{ route('ticket_booking') }}/"+ s +"/"+ e +"/"+ day +"/"+ train_name;
            })
        })
    </script>

    @if(!session('error'))
        <label>您查詢{{ $day }}班次，從{{ $s_c }}到{{ $e_c }}的班次如下</label><p/>
    @else
        <label>查無指定條件的車次，請更換條件再查詢</label><p/>
    @endif
    
    <table width="70%" border="1">
        <tr>
            <th>車種</th>
            <th>列車編號</th>
            <th>發車站/終點站</th>
            <th>預計發車時間</th>
            <th>預計到達時間</th>
            <th>行駛時間</th>
            <th>票價</th>
            <th>訂票</th>
        </tr>
        @foreach($train as $val)
        <tr>
            <th>{{ $val['type'] }}</th>
            <th>{{ $val['train_name'] }}</th>
            <th>{{ $val['s_e'] }}</th>
            <th>{{ $val['s_time'] }}</th>
            <th>{{ $val['e_time'] }}</th>
            <th>{{ $val['all_time'] }}</th>
            <th>{{ $val['all_money'] }}</th>
            <th>
                <button class="booking" train_name="{{ $val['train_name'] }}" s="{{ $s }}" e="{{ $e }}" day="{{ $day }}">訂票</button>
            </th>
        </tr>
        @endforeach
    </table>

@endsection
    


