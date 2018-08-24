@extends('start')
@section('text')
    <script>
        $(function(){
            $("#select").click(function(){
                if($("#train_id").val() == ""){
                    alert("車次代碼未填");
                }else{
                    location.href='{{ route("train_data") }}/'+$("#train_id").val();
                }
            })
            $("#ok").click(function(){
                var train_name = $(this).attr('train_name');
                location.href='{{ route("train_booking") }}/'+train_name;
            })
        })
    </script>
    
    車次代碼<input type="text" id="train_id" value="{{ $train_name }}">
    <button id="select">確定</button><p/>

    @if($train_name != '')
        車次代碼:{{ $train_name }}<br/>
        行駛星期:{{ $day }}
        <table width="400px" border="1">
            <tr>
                <th>車站</th>
                <th>抵達時間</th>
                <th>發車時間</th>
            </tr>
            @foreach($station as $val)
                <tr>
                    <th>{{ $val['station'] }} </th>
                    <th>{{ $val['e_time'] }}</th>
                    <th>{{ $val['s_time'] }}</th>
                </tr>
            @endforeach
        </table>
        <button id="ok" train_name="{{ $train_name }}">訂票</button>
    @endif

@endsection
    


