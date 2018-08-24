@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{  __('查詢列車') }}</div>

<div class="card-body">
    <form method="GET" id="form">
        @csrf
        <div class="form-group row">
            <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('列車代碼') }}</label>

            <div class="col-md-6">
                <select name="code" id="code" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}">
                    @foreach($trains as $train)
                    <option value="{{ $train->code }}">{{ $train->code }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">{{ __('確定') }}</button>
            </div>
        </div>
    </form>
    
    @if($data->id)
    <div class="alert alert-primary" role="alert">
        <span>行駛星期:</span>
        {{ $train->day }}
    </div>
    @endif

    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>車站</th>
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
                $time2 = $time1 + $stop->stop_time;
            @endphp
            <tr>
                <th>{{ $stations->get($stop->station_id-1)->chinese }}</th>
                @if($key > 0)
                    <th>{{ date('H:i:s', strtotime( date('Y-m-d').' '.$train->start_time ."+". $time1."min")) }}</th>
                @else
                <th></th>
                @endif
                @if($key < $data->stops->count()-1 )
                    <th>{{ date('H:i:s', strtotime( date('Y-m-d').' '.$train->start_time ."+". $time2."min")) }}</th>
                @else
                    <th></th>
                @endif
            </tr>
        @endforeach
    </table>
    <button class="btn btn-success" id="out">{{ __('訂票') }}</button>
</div>
@endsection

@section('js')
<script>
    $("#form").submit(function(event){
        event.preventDefault();
        location.href="{{ url('type_select')}}/"+$("#code").val();
        return false;
    })
    $("#out").click(function(event){
        event.preventDefault();
        location.href="{{ url('booking')}}/"+$("#code").val();
        return false;
    })
</script>
@endsection

