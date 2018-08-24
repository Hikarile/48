@extends('start')
@section('text')
    <script>
        $(function(){
            $("#ok").click(function(){
                var train_name = $("#train_name").val();
                var day = $("#day").val();
                var cellphone = $("#cellphone").val();
                var booking_id = $("#booking_id").val();
                var station_s = $("#station_s").val();
                var station_e = $("#station_e").val();
                if(train_name == "" ||day == "" ||cellphone == "" ||booking_id == "" ||station_s == "" ||station_e == ""){
                    alert("請填寫完成");
                    return false;
                }
                location.href='{{ route("ticket") }}/'+train_name+'/'+day+'/'+cellphone+'/'+booking_id+'/'+station_s+'/'+station_e;
            })
            $("#reset").click(function(){
                location.href = '{{ route("ticket") }}';
            })
            $(".del").click(function(){
                location.href = '{{ route("ticket_del") }}/'+$(this).attr('booking');
            })
        })
    </script>

    <label>查詢條件</label>
    <form>
        車次:<input id="train_name" value="{{ $train_name }}" required><br/>
        發車日期:<input type="date" id="day" value="{{ $day }}" required><br/>
        手機號碼:<input id="cellphone" value="{{ $cellphone }}" required><br/>
        訂票編號:<input id="booking_id" value="{{ $booking_id }}" required><br/>
        起點站:
        <select name="station_s" id="station_s">
            @foreach($station as $val)
                @if($station_s != "" && $station_s == $val['english']){
                    <option value="{{ $val['english'] }}" selected>{{ $val['chinese'] }}</option>
                @else
                    <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
                @endif
            @endforeach
        </select>
        終點站:
        <select name="station_e" id="station_e">
            @foreach($station as $val)
                @if($station_e != "" && $station_e == $val['english']){
                    <option value="{{ $val['english'] }}" selected>{{ $val['chinese'] }}</option>
                @else
                    <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
                @endif
            @endforeach
        </select><br/>
        <input type="button" value="確定" id="ok">
        <input type="button" value="取消" id="reset">
    </form><p/>

    <table width="60%" border="1">
        <tr>
            <th>訂票時間</th>
            <th>發車時間</th>
            <th>車次</th>
            <th>起訖站</th>
            <th>張數</th>
            <th>編輯</th>
        </tr>
        @foreach($booking as $val)
        <tr>
            <th>{{ $val['booking_day'] }}</th>
            <th>{{ $val['time'] }}</th>
            <th>{{ $val['train_id'] }}</th>
            <th>{{ $val['station'] }}</th>
            <th>{{ $val['count'] }}</th>
            <th>
                @if($val['del'] == 1)
                    已取消
                @else
                    <button class="del" booking="{{ $val['id'] }}">取消定票</button>
                @endif
            </th>
        </tr>
        @endforeach
    </table>

@endsection
    


