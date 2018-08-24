@extends('start')
@section('text')
    <style>
        #back1{
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height:850px;
        }
        #back2{
            position: relative;
            padding: 50px;
            top: 30%;
            width: 300px;
            height:200px;
            background-color: #62b4cf;
            border-radius: 20px;
        }
    </style>
    
    <script>
        var station_c = new Array();
        var station_e = new Array();
        var stop_time = new Array();
        var time = new Array();
        var money = new Array();
        var ddd = 0;

        $(function(){
            $("#car_people, #car_count").change(function(){
                $("#car_all").val( $("#car_people").val() * $("#car_count").val());
            })
            $("#station_add").click(function(){
                $("#back1").show();
            });
            $("#stop_no").click(function(){
                $("#back1").hide();
            })
            $("#stop_ok").click(function(){
                if($("#stop_time").val() == 0 || $("#time").val() == 0 || $("#money").val() == 0){
                    alert('不能為0');
                    return false;
                }
                
                var s = $("#station").val().split(',');
                station_c.push(s[0]);
                station_e.push(s[1]);
                stop_time.push($("#stop_time").val());
                time.push($("#time").val());
                money.push($("#money").val());

                table_add();
                $("#back1").hide();
            })
            $("input[type='checkbox']").change(function(){
                if($(this).attr('c') == "0"){
                    $(this).attr('c', "1");
                    ddd += 1;
                }else if($(this).attr('c') == "1"){
                    $(this).attr('c', "0");
                    ddd-=1;
                }
            })
            $("#sub").submit(function(){
                if($("#train_name").val() == ""){
                    alert('列車代號未填');
                    return false;
                }else if(ddd == 0){
                    alert('未選擇發車星期');
                    return false;
                }else if(station_c.length < 2){
                    alert('車站至少要有兩站');
                    return false;
                }
            })
        })
        
        function table_add(){
            $(".table_remve").remove();
            $.each(station_c, function(i){
                if(i == 0){
                    var html = '<tr class="table_remve" id="tr_add'+i+'">'+
                            '<th>'+(i+1)+'</th>'+
                            '<th>'+station_c[i]+' <input type="hidden" name="station_c[]" value="'+station_c[i]+'"> </th>'+
                            '<th>'+stop_time[i]+' <input type="hidden" name="stop_time[]" value="'+stop_time[i]+'"> </th>'+
                            '<th>'+
                                '行駛時間<input type="hidden" name="time[]" value="0"><br/>'+
                                '票價<input type="hidden" name="money[]" value="0">'+
                            '</th>'+
                            '<th><input type="button" value="移除" onclick="del_station('+i+')"></th>'+
                        '</tr>';
                }else{
                    var html = '<tr class="table_remve" id="tr_add'+i+'">'+
                            '<th>'+(i+1)+'</th>'+
                            '<th>'+station_c[i]+' <input type="hidden" name="station_c[]" value="'+station_c[i]+'"> </th>'+
                            '<th>'+stop_time[i]+' <input type="hidden" name="stop_time[]" value="'+stop_time[i]+'"> </th>'+
                            '<th>'+
                                '行駛時間'+time[i]+' <input type="hidden" name="time[]" value="'+time[i]+'">   <br/>'   +
                                '票價'+money[i]+' <input type="hidden" name="money[]" value="'+money[i]+'"> '+
                            '</th>'+
                            '<th><input type="button" value="移除" onclick="del_station('+i+')"></th>'+
                        '</tr>';
                }
                if(i == 0){
                    $("#station_s").val(station_c[i]);
                }if(i == station_c.length-1){
                    $("#station_e").val(station_c[i]);
                }
                $("#count").val(i);
                $("#start").append(html);
            })
        }
        function del_station($id){
            station_c.splice($id,1);
            station_e.splice($id,1);
            stop_time.splice($id,1);
            time.splice($id,1);
            money.splice($id,1);
            table_add();
        }
    </script>

    <div id="back1" hidden>
        <div id="back2">
            車站名稱:<select id="station">
                @foreach($station as $val)  
                    <option id="{{ $val['english'] }}" value="{{ $val['chinese'] }},{{ $val['english'] }}">{{ $val['chinese'] }}</option>
                @endforeach
            </select><br/>
            停站時間(分):
            <input type="number" id="stop_time" min="1" value="0"><br/>
            行駛時間:
            <input type="number" id="time" min="1" value="0"><br/>
            票價:
            <input type="number" id="money" min="1" value="0"><p/>
            <input type="button" value="確定" id="stop_ok">
            <input type="button" value="取消" id="stop_no">
        </div>
    </div>

    <label>新增列車</label><p/>

    <form action="{{ route('train_create') }}" method="post" id="sub">
        {{ csrf_field() }}
        車種:
        <select name="type_id" id="type_id">
            @foreach($type as $val)
            <option value="{{ $val['id'] }}">{{ $val['type'] }}</option>
            @endforeach
        </select>
        列車代號:<input type="text" name="train_name" id="train_name"><p/>

        發車星期:
        <input type="checkbox" name="mon" id="mon" value="1" c="0">星期一
        <input type="checkbox" name="tue" id="tue" value="1" c="0">星期二
        <input type="checkbox" name="wed" id="wed" value="1" c="0">星期三
        <input type="checkbox" name="thu" id="thu" value="1" c="0">星期四
        <input type="checkbox" name="fri" id="fri" value="1" c="0">星期五
        <input type="checkbox" name="sat" id="sat" value="1" c="0">星期六
        <input type="checkbox" name="sun" id="sun" value="1" c="0">星期日<p/>

        單一車廂載客數:<input type="number" name="car_people" id="car_people" min="1" value="1"><br/>
        車廂數輛:<input type="number" name="car_count" id="car_count" min="1" value="1"><br/>
        載客總數:<input type="number" name="car_all" id="car_all" min="5" value="1" readonly><br/>
        發車時間:<input type="time" name="station_s_time" id="station_s_time"><p/>
        
        行經車站:<input type="button" id="station_add" value="新增車站">
        <table width="50%" border="1" id="start">
            <tr>
                <th>車次</th>
                <th>車站名稱</th>
                <th>停站時間(分)</th>
                <th>前一站到該站的行駛時間(分)及票價</th>
                <th>編輯</th>
            </tr>
        </table><p/>

        <input type="hidden" name="station_s" id="station_s" name="station_s" value="">
        <input type="hidden" name="station_e" id="station_e" name="station_e" value="">
        <input type="hidden" name="count" id="count" name="count" value="0">

        <button>確定</button>
    </form>

@endsection
