@extends('layouts.app')

@section('css')
<style>
    #img {
        width: 300px;
        flex-wrap: wrap;
    }

    #img > img {
        width: 100px;
        height: 100px;
        box-sizing: border-box;
    }

    #img > img.active {
        border: 2px solid red;
        padding: 10px;
    }
</style>
@endsection

@section('content')
<div class="card-header">{{  __('修改車種') }}</div>

<div class="card-body">
    <form method="POST" id="form" action="{{ url('book_create')}}">
        @csrf
        
        <div class="form-group row">
            <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

            <div class="col-md-6">
                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>

                @if ($errors->has('phone'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        
        <div class="form-group row">
            <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('車次代碼') }}</label>

            <div class="col-md-6">
                <select name="code" id="code" class="form-control">
                    @foreach($trains as $train)
                    <option value="{{ $train->code }}" {{ request()->day?:request()->type == $train->code?'selected':'' }} >{{ $train->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('搭乘日期') }}</label>

            <div class="col-md-6">
                <input id="day" type="date" class="form-control" name="day" value="{{ request()->day?:date('Y-m-d') }}" required autofocus>
            </div>
        </div>

        <div class="form-group row">
            <label for="count" class="col-sm-4 col-form-label text-md-right">{{ __('車票張數') }}</label>

            <div class="col-md-6">
                <input id="count" type="number" class="form-control" min="1" max="100" name="count" value="{{ old('count') }}" required autofocus>

                @if ($errors->has('count'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('count') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="from" class="col-sm-4 col-form-label text-md-right">{{ __('起程站') }}</label>

            <div class="col-md-6">
                <select name="from" id="from" class="form-control">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ request()->from == $station->english?'selected':'' }}>{{ $station->chinese }}</option>
                    @endforeach
                </select>

                @if ($errors->has('from'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('from') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="to" class="col-sm-4 col-form-label text-md-right">{{ __('到達站') }}</label>

            <div class="col-md-6">
                <select name="to" id="to" class="form-control">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ request()->to == $station->english?'selected':'' }}>{{ $station->chinese }}</option>
                    @endforeach
                </select>

                @if ($errors->has('to'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('to') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="modal fade" id="station_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="text"></h3>
                    </div>
                    <div class="modal-body">
                        <div id="img"></div>
                    </div>
                    <div class="modal-foot">
                        <a href="#" class="btn btn-primary" id="change">更新驗證碼</a>
                        <a href="#" class="btn btn-success" id="ok">送出驗證碼</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#station_modal" id="imageimage">回答驗證碼</a>
                <button type="submit" class="btn btn-primary">
                    {{ __('確定') }}
                </button>
            </div>
        </div>
    </form>

</div>
@endsection

@section('js')
<script>
    var tf = false;
    var rand = 0;
    var image_rand = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    var data = ['img0','img1','img2','img3'];
    var img = [[6, 8, 9], [3, 6, 7], [2, 5, 7, 8], [1, 3, 4]];
    var text = ["選取所有包含樹木的圖片", "選取圖片中含有小貨車的所有圖片", "選取圖片中含有青草的所有圖片", "選取圖片中含有公車的所有圖片"];
    
    $("#form").submit(function(){
        if(! tf){
            alert('還未進行驗證');
            return false;
        }
    })

    $("#ok").click(function(){
        if(tf){
            alert('已驗證成功');
            return false;
        }
    });

    $('#change').click(function () {
        rand = parseInt(Math.random()*4);
        image_rand.sort(function() {
            return .5 - Math.random();
        });
        html = [];
        for(i = 0; i <= 8 ; i++){
            html.push('<img src="{{ asset("public/img/img") }}'+rand+'/p'+image_rand[i]+'.jpg" alt="" number="'+image_rand[i]+'">');
        }
        $("#text").text(text[rand]);
        $('#img').html(html);
    }).click();

    $(document).on('click', '#img img', function () {
        $(this).toggleClass('active');
    });

    $('#ok').click(function () {
        let da = [];
        $('#img img.active').each(function (index, img) {
            da.push($(img).attr('number'));
        });
        da.sort();
        
        if(img[rand].toString() == da.toString()){
            tf = true;
            alert('驗證成功');
            $("#station_modal").click();
        }else{
            tf = false;
            alert('驗證失敗');
            $("#change").click();
        }
    });
</script>
@endsection
