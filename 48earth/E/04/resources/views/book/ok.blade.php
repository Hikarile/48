@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{ __('確認訂票') }}</div>

<div class="card-body">
    <div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

        <div class="col-md-6">
            <input id="code" type="text" class="form-control" name="code" value="{{ $booking->code }}" required readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

        <div class="col-md-6">
            <input id="phone" type="text" class="form-control" name="phone" value="{{ $booking->phone }}" required readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

        <div class="col-md-6">
            <input id="day" type="text" class="form-control" name="day" value="{{ $booking->time }}" required readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="train_name" class="col-md-4 col-form-label text-md-right">{{ __('車次') }}</label>

        <div class="col-md-6">
            <input class="form-control" name="from" value="{{$booking->train_name  }}" required readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="from" class="col-md-4 col-form-label text-md-right">{{ __('起程站') }}</label>

        <div class="col-md-6">
            <input class="form-control" name="from" value="{{ $station->get($booking->from-1)->first()->chinese }}" required readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="to" class="col-md-4 col-form-label text-md-right">{{ __('到達站') }}</label>

        <div class="col-md-6">
            <input class="form-control" name="from" value="{{ $station->get($booking->to-1)->first()->chinese }}" required readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="count" class="col-md-4 col-form-label text-md-right">{{ __('訂票張數') }}</label>

        <div class="col-md-6">
            <input id="count" type="text" class="form-control" name="count" value="{{ $booking->count }}" required readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="money" class="col-md-4 col-form-label text-md-right">{{ __('單張票價') }}</label>

        <div class="col-md-6">
            <input id="money" type="text" class="form-control" name="money" value="{{ $booking->money }}" required readonly>
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <a class="btn btn-success" href="{{ url('booking') }}">確定</a>
        </div>
    </div>

</div>

@endsection

@section('js')
@endsection
