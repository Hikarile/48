@extends('layouts.app')

@section('content')
    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ $booking->code }}" disabled>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ $booking->phone }}" disabled>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ $booking->start_time }}" disabled>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('車次') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ $booking->train_name }}" disabled>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('起訖站') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ $station->get($booking->start)->chinese .'/'. $station->get($booking->end)->chinese }}" disabled>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('張數') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ $booking->count }}" disabled>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('車票單價') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ number_format($booking->money, 0, '', ',') }}" disabled>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-4 col-form-label text-md-right">{{ __('總票價') }}</label>

        <div class="col-md-4">
            <input class="form-control" value="{{ number_format($booking->count * $booking->money, 0, '', ',') }}" disabled>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <a class="btn btn-primary" href="{{ url('booking') }}">確定</a>
        </div>
    </div>
@endsection