@extends('layouts.app')

@section('content')
<div class="card-header">{{request()->id? __('修改車種'): __('新增車種') }}</div>

<div class="card-body">
    <form method="POST" action="{{ url('login/type/create', request()->id) }}">
        @csrf

        <div class="form-group row">
            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __('車種名稱') }}</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $types->name?:old('name') }}" required autofocus>

                @if ($errors->has('name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="speed" class="col-md-4 col-form-label text-md-right">{{ __('最高時速') }}</label>

            <div class="col-md-6">
                <input id="speed" type="number" class="form-control{{ $errors->has('speed') ? ' is-invalid' : '' }}" value="{{ $types->speed?:old('speed') }}" name="speed" required>

                @if ($errors->has('speed'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('speed') }}</strong>
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
