@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('列車管理') }}</div>

                <div class="card-body">
                <form method="GET">
                        @csrf
                        
                        <div class="form-group row">
                            <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('車種') }}</label>

                            <div class="col-md-4 form-inline">
                                <select class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" required name="type" id="type">
                                    @foreach($types as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('type'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="day" class="col-sm-4 col-form-label text-md-right">{{ __('搭乘日期') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="day" type="date" class="form-control{{ $errors->has('day') ? ' is-invalid' : '' }}" name="day" value="{{ date('Y-m-d') }}" required autofocus>

                                @if ($errors->has('day'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('day') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="from" class="col-sm-4 col-form-label text-md-right">{{ __('起程站') }}</label>

                            <div class="col-md-4">
                                <select class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}" name="from" id="from" required>
                                    @foreach ($stations as $station)
                                    <option value="{{ $station->english }}" station_name="{{ $station->chinese }}"}>{{ $station->chinese }}</option>
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
                                    <option value="{{ $station->english }}" station_name="{{ $station->chinese }}"}>{{ $station->chinese }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('to'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('確定') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $('form').submit(function (event) {
        event.preventDefault();

        var type = $('#type').val();
        var day = $('#day').val();
        var from = $('#from').val();
        var to = $('#to').val();
        location.href = '{{ url("type/select") }}' + '/' + type + '/' + day + '/' + from + '/' + to;
    });
</script>
@endsection