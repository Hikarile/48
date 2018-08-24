@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{ request()->id?__('修改車站'):  __('新增車站') }}</div>

<div class="card-body">
    <form method="POST" action="{{ url('login/station/create', $station->id)}}">
        @csrf

        <div class="form-group row">
            <label for="chinese" class="col-sm-4 col-form-label text-md-right">{{ __('車站名稱') }}</label>

            <div class="col-md-6">
                <input id="chinese" class="form-control{{ $errors->has('chinese') ? ' is-invalid' : '' }}" name="chinese" value="{{ old('chinese')?:$station->chinese }}" required autofocus>

                @if ($errors->has('chinese'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('chinese') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="english" class="col-md-4 col-form-label text-md-right">{{ __('英文名稱') }}</label>

            <div class="col-md-6">
                <input id="english" class="form-control{{ $errors->has('english') ? ' is-invalid' : '' }}" name="english" value="{{ old('english')?:$station->english }}" required>

                @if ($errors->has('english'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('english') }}</strong>
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
@endsection
