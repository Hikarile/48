@extends('start')
@section('text')
    <script>
        $(function(){
            $("#ok").click(function(){
                if($("#booking_id").val() != "" && $("#cellphone").val() != ""){
                    location.href='{{ route("ticket_select") }}/'+$("#booking_id").val()+'/'+$("#cellphone").val();
                }else if($("#booking_id").val() != "" && $("#cellphone").val() == ""){
                    location.href='{{ route("ticket_select") }}/'+$("#booking_id").val()+'/_';
                }else if($("#booking_id").val() == "" && $("#cellphone").val() != ""){
                    location.href='{{ route("ticket_select") }}/_/'+$("#cellphone").val();
                }
            })

            $(".del").click(function(){
                location.href = '{{ route("ticket_del") }}/'+$(this).attr('booking');
            })
        })
    </script>

    編號<input name ="booking_id" id="booking_id" value="{{ $booking_id }}">
    手機<input name ="cellphone" id="cellphone" value="{{ $cellphone }}">
    <button id="ok">查詢</button><p/>

    <table width="60%" border="1">
        <tr>
            <th>訂票編號</th>
            <th>訂票時間</th>
            <th>發車時間</th>
            <th>車次</th>
            <th>起訖站</th>
            <th>張數</th>
            <th>編輯</th>
        </tr>
        @foreach($booking as $val)
        <tr>
            <th>{{ $val['booking_id'] }}</th>
            <th>{{ $val['booking_day'] }}</th>
            <th>{{ $val['start_day'] }}</th>
            <th>{{ $val['train_id'] }}</th>
            <th>{{ $val['station'] }}</th>
            <th>{{ $val['count'] }}</th>
            <th>
                @if( $val['del'] == 0)
                <button class="del" booking="{{ $val['id'] }}">取消定票</button>
                @else
                已取消
                @endif
            </th>
        </tr>
        @endforeach
    </table>
@endsection