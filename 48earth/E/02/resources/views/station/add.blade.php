@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('login/station/create', $station->id)}}">
                        @csrf

                        <div class="form-group row">
                            <label for="chinese" class="col-sm-4 col-form-label text-md-right">{{ __('車站名稱(中文)') }}</label>

                            <div class="col-md-6">
                                <input id="chinese" type="text" class="form-control{{ $errors->has('chinese') ? ' is-invalid' : '' }}" name="chinese" value="{{ old('chinese') ?: $station->chinese }}" required autofocus>

                                @if ($errors->has('chinese'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('chinese') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="english" class="col-md-4 col-form-label text-md-right">{{ __('車站名稱(英文)') }}</label>

                            <div class="col-md-6">
                                <input id="english" type="text" class="form-control{{ $errors->has('english') ? ' is-invalid' : '' }}" name="english" value="{{ old('english')?: $station->english }}" required>

                                @if ($errors->has('english'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('english') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button station="submit" class="btn btn-primary">
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
