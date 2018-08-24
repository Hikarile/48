@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{  __('新增列車') }}</div>

<div class="card-body">
    <form method="GET" id="form">
        @csrf

        <div class="form-group row">
            <label for="type" class="col-sm-4 col-form-label text-md-right">{{ __('車種') }}</label>

            <div class="col-md-6">
                <select name="type" id="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                    @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
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
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

            <div class="col-md-6">
                <input id="day" type="date" class="form-control{{ $errors->has('day') ? ' is-invalid' : '' }}" name="day" value="{{ date('Y-m-d') }}" required>

                @if ($errors->has('day'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('day') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="from" class="col-md-4 col-form-label text-md-right">{{ __('起程站') }}</label>

            <div class="col-md-6">
                <select name="from" id="from" class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->english }}">{{ $station->chinese }}</option>
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
            <label for="to" class="col-md-4 col-form-label text-md-right">{{ __('到達站') }}</label>

            <div class="col-md-6">
                <select name="to" id="to" class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->english }}">{{ $station->chinese }}</option>
                    @endforeach
                </select>
                @if ($errors->has('to'))
                    <span class="invalid-feedback">
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
@endsection

@section('js')
    <script>
        $("#form").submit(function(){
            var from = $("#from").val();
            var to = $("#to").val();
            var type = $("#type").val();
            var day = $("#day").val();
            location.href='{{ url("train") }}/'+type+"/"+day+"/"+from+"/"+to;
            return false;
        });
    </script>
@endsection
