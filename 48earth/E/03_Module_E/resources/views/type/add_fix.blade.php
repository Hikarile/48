@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ request()->id == '' ?  __('新增車種')  :  __('修改車種')  }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('login/type/create', $types->id)}}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __(' 車種名稱') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') ?: $types->name }}" required autofocus>

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
                                <input id="speed" type="number" class="form-control{{ $errors->has('speed') ? ' is-invalid' : '' }}" min="0" value="{{ old('speed') ?: $types->speed }}" name="speed" required>

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
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection