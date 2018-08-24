@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('登入後台') }}</div>

<div class="card-body">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group row">
            <label for="ac" class="col-sm-4 col-form-label text-md-right">{{ __('帳號') }}</label>

            <div class="col-md-6">
                <input id="ac" type="text" class="form-control{{ $errors->has('ac') ? ' is-invalid' : '' }}" name="ac" value="{{ old('ac') }}" required autofocus>

                @if ($errors->has('ac'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('ac') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密碼') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('登入') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
