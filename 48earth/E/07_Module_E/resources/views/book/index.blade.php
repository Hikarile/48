@extends('layouts.app')
@section('css')
<style>
    #img{
        width:300px;
        flex-wrap:wrap
    }
    #img>img{
        width:100px;
        height:100px;
        box-sizing:border-box;
    }
    #img>img.c{
        border:2px #f00 solid;
        padding:10px;
    }
</style>
@endsection
@section('content')
<div class="card-header">{{ __('預訂車票') }}</div>

<div class="card-body">
    <form method="POST" id="form" action="{{ url('book/create') }}">
        @csrf

        <div class="form-group row">
            <label for="train_name" class="col-sm-4 col-form-label text-md-right">{{ __('車次代碼') }}</label>

            <div class="col-md-6">
                <select name="train_name" id="train_name" class="form-control{{ $errors->has('train_name') ? ' is-invalid' : '' }}">
                    @foreach($trains as $train)
                    <option value="{{ $train->code }}" {{ request()->code == $train->name?'selected':'' }}>{{ $train->code }}</option>
                    @endforeach
                </select>

                @if ($errors->has('train_name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('train_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

            <div class="col-md-6">
                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" name="phone" required>

                @if ($errors->has('phone'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('乘車日期') }}</label>

            <div class="col-md-6">
                <input id="day" type="date" class="form-control{{ $errors->has('day') ? ' is-invalid' : '' }}" step="1" value="{{ request()->day?:date('Y-m-d') }}" step name="day" required>

                @if ($errors->has('day'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('day') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="from" class="col-sm-4 col-form-label text-md-right">{{ __('起程站') }}</label>

            <div class="col-md-6">
                <select name="from" id="from" class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ request()->from == $station->english?'selected':''  }}>{{ $station->chinese }}</option>
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
                <select name="to" id="to" class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}"  {{ request()->to == $station->english?'selected':''  }}>{{ $station->chinese }}</option>
                    @endforeach
                </select>

                @if ($errors->has('to'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('to') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="count" class="col-md-4 col-form-label text-md-right">{{ __('車票張數') }}</label>

            <div class="col-md-6">
                <input id="count" type="number" class="form-control{{ $errors->has('count') ? ' is-invalid' : '' }}" value="{{ old('count')}}" name="count" required min="1" max="100">

                @if ($errors->has('count'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('count') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div id="aaa" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="text"></h3>
                    </div>
                    <div class="modal-header">
                        <div id="img"></div>
                    </div>
                    <div class="modal-header">
                        <a href="#" class="btn btn-primary" id="change">更換驗證碼</a>
                        <a href="#" class="btn btn-success" id="ok">驗證驗證碼</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#aaa" id="click">問答驗證碼</a>
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
    var number = [1,2,3,4,5,6,7,8,9];
    var text = [['選取所有包含樹木的圖片'],['選取圖片中含有小貨車的所有圖片'],['選取圖片中含有青草的所有圖片'],['選取圖片中含有公車的所有圖片']];
    var da = [[6,8,9],[3,6,7],[2,5,7,8],[1,3,4]];
    var fil = ['img0','img1','img2','img3'];

    $("#form").submit(function(){
        if(!tf){
            alert('還未驗證成功');
            return false;
        }
    })
    $("#click").click(function(){
        if(tf){
            alert('已驗證成功');
            return false;
        }
    })
    $("#change").click(function(){
        var rand = parseInt(Math.random()*4);
        number.sort(function(){
            return .5*Math.random();
        });
        html = [];
        for(i = 0; i <9; i++){
            html.push(`<img src="{{ asset('public/img/img')}}${rand}/p${number[i]}.jpg" number="${number[i]}" alt="">`);
        }
        $("#img").html(html);
        $("#text").text(text[rand]);
    }).click();
    $(document).on('click','img', function(){
        $(this).toggleClass('c');
    })
    $("#ok").click(function(){
        var aa = [];
        $("#img img.c").each(function(index, img){
            aa.push($(img).attr('number'));
        })
        aa.sort();
        
        if(aa.toString() == da[rand].toString()){
            alert('驗證成功');
            tf = true;
            $("#aaa").click();
        }else{
            alert('驗證失敗');
            $("#change").click();
        }
    })
</script>
@endsection
