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
            padding:10px;
            top: 20%;
            width: 400px;
            height:400px;
            background-color: #62b4cf;
        }
        .img{
            width:100px;
            height:100px;
            border:2px  #000;
        }
    </style>
    
    <script>
        var url = Array();
        var da = Array();
        var text = Array();
        var count = -1
        var aaa = "";

        $(function(){
            $("#c1").click(function(){
                $("#back1").show();
            })
            $("#c2").click(function(){
                $("#back1").hide();
            })
            $("#v_reset").click(function(){
                count += 1;
                if(count == 5){
                    count = 1;
                }

                $("#text").text(text[count]);
                for(i=1;i<=9;i++){
                    $("#img"+i).css('width', "100px");
                    $("#img"+i).css('height', "100px");
                    $("#img"+i).css('margin', "0px");
                    $("#img"+i).attr('src', "public/img/img"+count+"/p"+i+".jpg");
                    $("#img"+i).attr('number', i);
                    $("#img"+i).attr('c', "0");
                }
            }).click();
            $(".img").click(function(){
                aaa = "";
                if($(this).attr('c') == "0"){
                    $(this).css('width', "80px");
                    $(this).css('height', "80px");
                    $(this).css('margin', "10px");
                    $(this).attr('c', "1");
                }else{
                    $(this).css('width', "100px");
                    $(this).css('height', "100px");
                    $(this).css('margin', "0px");
                    $(this).attr('c', "0");
                }
                for(i=1;i<=9;i++){
                    if($("#img"+i).attr('c') == "1"){
                        aaa = aaa+i+" ";
                    }
                }
            })

            $("#sub").submit(function(){
                if(aaa != da[count]){
                    alert('驗證碼未通過')
                    return false;
                }else{
                    alert('yes')
                    return false;
                }
            })
        })

        function start(){
            @foreach($verification as $val)
                url.push("{{ $val['url'] }}");
                da.push("{{ $val['da'] }}");
                text.push("{{ $val['text'] }}");
            @endforeach
        }
       start();
    </script>

    <div id="back1" hidden>
        <div id="back2">
            <label id="text"></label><p/>
            <img class="img" id="img1" c="0">
            <img class="img" id="img2" c="0">
            <img class="img" id="img3" c="0"><br/>
            <img class="img" id="img4" c="0">
            <img class="img" id="img5" c="0">
            <img class="img" id="img6" c="0"><br/>
            <img class="img" id="img7" c="0">
            <img class="img" id="img8" c="0">
            <img class="img" id="img9" c="0"><br/>
            <button id="c2">確定</button>
            <button id="v_reset">重置</button>
        </div>
    </div>
    
    <form action="{{ route('booking_create') }}" method="post" id="sub">
        {{ csrf_field() }}
        手機號碼:<input name="cellphone" id="cellphone"><br/>
        起程站:
        <select name="station_s" id="station_s">
            @foreach($station as $val)
                @if($s == $val['english'])
                <option value="{{ $val['english'] }}" selected>{{ $val['chinese'] }}</option>
                @else
                <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
                @endif
            @endforeach
        </select><br/>
        到達站:
        <select name="station_e" id="station_e">
            @foreach($station as $val)
                @if($e == $val['english'])
                <option value="{{ $val['english'] }}" selected>{{ $val['chinese'] }}</option>
                @else
                <option value="{{ $val['english'] }}">{{ $val['chinese'] }}</option>
                @endif
            @endforeach
        </select><br/>
        搭乘日期:<input type="date" name="day" id="day" value="{{ empty($day) ? '' : $day }}"><br/>
        車次代碼:<input type="number" name="train_id" id="train_id" value="{{ empty($train_type) ? '' : $train_type }}"><br/>
        車票張數:<input type="number" name="count" id="count" value="1"><br/>
        <input type="button" id="c1" value="回答驗證碼">
        <button>確定</button>
    </form>

    
@endsection
    


