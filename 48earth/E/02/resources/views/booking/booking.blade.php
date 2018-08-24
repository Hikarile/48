@extends('layouts.app')

@section('css')
    <style>
        .recaptcha-images {
            width: 300px;
            flex-wrap: wrap;
        }

        .recaptcha-images > img {
            width: 100px;
            height: 100px;
            box-sizing: border-box;
        }

        .recaptcha-images > img.active {
            border: 2px solid red;
            padding: 10px;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('預訂車票') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('booking/create')}}" id="from">
                        @csrf

                        <div class="form-group row">
                            <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="number" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="" required autofocus>

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="from" class="col-sm-4 col-form-label text-md-right">{{ __('啟程站') }}</label>

                            <div class="col-md-4">
                                <select class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}" name="from" id="from" required>
                                    @foreach ($stations as $station)
                                    <option value="{{ $station->id }}" {{ request()->from == $station->english? 'selected' : '' }}>{{ $station->chinese }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('from'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="to" class="col-sm-4 col-form-label text-md-right">{{ __('到達站') }}</label>

                            <div class="col-md-4">
                                <select class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}" name="to" id="to" required>
                                    @foreach ($stations as $station)
                                    <option value="{{ $station->id }}" {{ request()->to == $station->english? 'selected' : '' }}>{{ $station->chinese }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('to'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="day" class="col-sm-4 col-form-label text-md-right">{{ __('搭乘日期') }}</label>

                            <div class="col-md-4">
                                <input id="day" type="date" class="form-control{{ $errors->has('day') ? ' is-invalid' : '' }}" name="day" value="{{ request()->day }}" required autofocus>  

                                @if ($errors->has('day'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('day') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __('車次代碼') }}</label>

                            <div class="col-md-4">
                                <select class="form-control{{ $errors->has('day') ? ' is-invalid' : '' }}" name="name" id="name" required>
                                    @foreach ($trains as $train)
                                    <option value="{{ $train->name }}">{{ $train->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="count" class="col-sm-4 col-form-label text-md-right">{{ __('車票張數') }}</label>

                            <div class="col-md-6">
                                <input id="count" type="number" class="form-control{{ $errors->has('count') ? ' is-invalid' : '' }}" min="1" max="100" name="count" value="1" required autofocus>

                                @if ($errors->has('count'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('count') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#recaptcha_modal" id="btn_recaptcha">問答驗證碼</a>
                                <button station="submit" class="btn btn-primary">
                                    {{ __('訂票') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
               
                <div class="modal fade" id="recaptcha_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 id="recaptcha_text"></h3>
                            </div>
                            <div class="modal-body">
                                <div class="recaptcha-images d-flex" id="recaptcha_images"></div>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-secondary" id="recaptcha_change" href="#">更換驗證碼</a>
                                <a class="btn btn-success" id="recaptcha_check" href="#">送出驗證碼</a>
                                <input id="recaptcha_base_url" type="hidden" value="{{ url('recaptcha') }}">
                                <input id="recaptcha_image_base_path" type="hidden" value="{{ asset('public/img/recaptcha') }}">
                                <input id="recaptcha_id" type="hidden">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        var tf = false;
        var or_rand = 0;
        var image_rand = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        var data = ['img0','img1','img2','img3'];
        var img = [[6, 8, 9], [3, 6, 7], [2, 5, 7, 8], [1, 3, 4]];
        var text = ["選取所有包含樹木的圖片", "選取圖片中含有小貨車的所有圖片", "選取圖片中含有青草的所有圖片", "選取圖片中含有公車的所有圖片"];
        
        $("#from").submit(function(){
            if(! tf){
                alert('還未進行驗證');
                return false;
            }
        })

        $("#btn_recaptcha").click(function(){
            if(tf){
                alert('已驗證成功');
                return false;
            }
        });

        $('#recaptcha_change').click(function () {
            do{
                var rand = parseInt(Math.random()*4);
                if(rand != or_rand){
                    or_rand = rand;
                    break;
                }
            } while(1 == 1);
            
            image_rand.sort(function() {
                return .5 - Math.random();
            });

            html = [];
            for(i = 0; i <= 8 ; i++){
                html.push('<img src="{{ asset("public/img/img") }}'+or_rand+'/p'+image_rand[i]+'.jpg" alt="" number="'+image_rand[i]+'">');
            }

            $("#recaptcha_text").text(text[rand]);
            $('#recaptcha_images').html(html);
        }).click();

        $(document).on('click', '.recaptcha-images img', function () {
            $(this).toggleClass('active');
        });

        $('#recaptcha_check').click(function () {
            let da = [];
            $('.recaptcha-images img.active').each(function (index, img) {
                da.push($(img).attr('number'));
            });
            da.sort();
            
            if(img[or_rand].toString() == da.toString()){
                tf = true;
                alert('驗證成功');
                $("#recaptcha_modal").click();
            }else{
                tf = false;
                alert('驗證失敗');
                $("#recaptcha_change").click();
            }
        });
    </script>
@endsection