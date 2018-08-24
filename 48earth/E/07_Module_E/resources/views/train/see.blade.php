@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('列車管理') }}</div>

<div class="card-body">
        @csrf

    <div class="form-group row">
        <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('車次代碼') }}</label>

        <div class="col-md-6">
            <select name="code" id="code" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}">
                @foreach($trains as $train)
                <option value="{{ $train->code }}" {{ request()->code == $train->code?'selected':'' }}>{{ $train->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button class="btn btn-primary" id="ok">
                {{ __('確定') }}
            </button>
        </div>
    </div>

    @if($data != null)
    <div class="alert alert-primary">
        {{ $data->day }}
    </div>
    @endif
    @if($data != null)
    <table class="table text-center table-hover">
        <tr class="bg-dark text-light">
            <th>車站名稱</th>
            <th>抵達時間</th>
            <th>發車時間</th>
        </tr>
        @php
            $time1 = 0;
            $time2 = 0;
        @endphp
        @foreach($data->stops as $key => $stop)
        @php
            $time1 += $stop->time;
            $time2 += $stop->time;
            $time2 += $stop->stop_time;
        @endphp
        <tr>
            <th>{{ $stations->get($stop->station_id-1)->chinese }}</th>
            @if($key != 0)
            <th>{{ date("H:i:s", strtotime(date("Y-m-d").$data->start_time."+".$time1."min")) }}</th>
            @else
            <th></th>
            @endif
            @if($key != $data->stops()->count()-1)
            <th>{{ date("H:i:s", strtotime(date("Y-m-d").$data->start_time."+".$time2."min")) }}</th>
            @else
            <th></th>
            @endif
        </tr>
        @endforeach
    </table>
    <button class="btn btn-success" onclick="location.href='{{ url('booking').'/'.$train->code }}'">{{ __('訂票') }}</button>
    @endif
</div>
@endsection

@section('js')
<script>
    $("#ok").click(function(){
        location.href='{{ url("see") }}/'+$("#code").val();
    })
</script>
@endsection
