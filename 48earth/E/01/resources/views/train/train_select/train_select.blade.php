@extends('start')
@section('text')
    <script>
        $(function(){
            $("#sub").click(function(){
                if($("#day").val() == ""){
                    alert("日期未填");
                    return false;
                }
                var station_s = $("#station_s").val();
                var station_e = $("#station_e").val();
                var type = $("#type").val();
                var day = $("#day").val();
                location.href = ""+'{{ route("train_select_create") }}'+'/'+station_s+'/'+station_e+'/'+type+'/'+day+"";
            })
        })
    </script>
    <form method="get">
        起程站:
        <select name="station_s" id="station_s">
            @foreach($station as $val)
            <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
            @endforeach
        </select><br/>
        終點站:
        <select name="station_e" id="station_e">
            @foreach($station as $val)
            <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
            @endforeach
        </select><br/>
        車種:
        <select name="type" id="type">
            @foreach($type as $val)
            <option value="{{ $val['id'] }}">{{ $val['type'] }}</option>
            @endforeach
        </select><br/>
        搭車日期:<input type="date" name="day" id="day"><br/>
        <input type="button" value="送出" id="sub">
    </form>

@endsection
    


