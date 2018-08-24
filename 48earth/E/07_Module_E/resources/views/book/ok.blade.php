@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('新增車種') }}</div>

<div class="card-body">
    <div class="form-group row">
        <label for="type_id" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

        <div class="col-md-6">
            <input type="text" value="{{ $books->phone }}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"required readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

        <div class="col-md-6">
            <input type="text" value="{{ $books->code }}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"required readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

        <div class="col-md-6">
            <input type="text" value="{{ $books->day }} {{ $books->time }}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"required readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="start_time" class="col-md-4 col-form-label text-md-right">{{ __('起訖站') }}</label>

        <div class="col-md-6">
            <input type="text" value="{{ $stations->get($books->from-1)->chinese }}/{{ $stations->get($books->to-1)->chinese }}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"required readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="people" class="col-md-4 col-form-label text-md-right">{{ __('張數') }}</label>

        <div class="col-md-6">
            <input type="text" value="{{ $books->count }}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"required readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="car" class="col-md-4 col-form-label text-md-right">{{ __('單張票價') }}</label>

        <div class="col-md-6">
            <input type="text" value="{{ $books->money }}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}"required readonly>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button class="btn btn-primary" onclick="location.href='{{ url('booking') }}'">
                {{ __('確定') }}
            </button>
        </div>
    </div>
</div>
@endsection

@section('js')
@endsection
