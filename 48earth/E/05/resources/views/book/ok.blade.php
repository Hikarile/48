@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{  __('確認車票') }}</div>

<div class="card-body">
    @csrf
    
    <div class="form-group row">
        <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

        <div class="col-md-6">
            <input class="form-control" value="{{ $books->phone }}" required autofocus readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

        <div class="col-md-6">
            <input class="form-control" value="{{ $books->code }}" required autofocus readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

        <div class="col-md-6">
            <input class="form-control" value="{{ $books->day }}{{ $books->time }}" required autofocus readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('車次') }}</label>

        <div class="col-md-6">
            <input class="form-control" value="{{ $books->train_name }}" required autofocus readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('起訖站') }}</label>

        <div class="col-md-6">
            <input class="form-control" value="{{ $stations->get($books->from-1)->chinese }}/{{ $stations->get($books->to-1)->chinese }}" required autofocus readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('張數') }}</label>

        <div class="col-md-6">
            <input class="form-control" value="{{ $books->count }}" required autofocus readonly>
        </div>
    </div>
    <div class="form-group row">
        <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('總價錢') }}</label>

        <div class="col-md-6">
            <input class="form-control" value="{{ $books->count*$books->money }}" required autofocus readonly>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <a class="btn btn-primary" href="#" onclick="location.href='{{ url('booking') }}'">{{ __('確定') }}</a>
        </div>
    </div>

</div>
@endsection

@section('js')
@endsection
