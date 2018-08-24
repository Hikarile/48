@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{ __('查詢結果') }}</div>

<div class="card-body">

    <div class="form-group row">
        <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('車次代碼') }}</label>

        <div class="col-md-6">
            <select name="code" id="code" class="form-control">
                @foreach($trains as $train)
                <option value="{{ $train->code }}" {{ request()->code == $train->code?'selected':'' }} >{{ $train->code }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <a class="btn btn-primary" href="#" id="aaa">
                {{ __('確定') }}
            </a>
        </div>
    </div>
    @if($datas->id != null)
        <div class="alert alert-primary">
            {{ $datas->day }}
        </div>
    @endif

    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>車站名稱</th>
            <th>抵達時間</th>
            <th>發車時間</th>
        </tr>
        @php
            $time1 = 0;
            $time2 = 0;
        @endphp
        @foreach($datas->stops as $key=> $stop)
        @php
            $time1 += $stop->time;
            $time2 += $stop->time;
            $time2 += $stop->stop_time;
        @endphp
        <tr>
            <th>{{ $stations->get($stop->station_id-1)->chinese }}</th>
            @if($key > 0)
            <th>{{ date("H:i:s", strtotime(date("Y-m-d").$datas->start_time." +".$time1."min" )) }}</th>
            @else
            <th></th>
            @endif
            @if($key != $datas->stops->count()-1)
            <th>{{ date("H:i:s", strtotime(date("Y-m-d").$datas->start_time." +".$time2."min" )) }}</th>
            @else
            <th></th>
            @endif
        </tr>
        @endforeach
    </table>
    @if($datas->id != null)
        <a class="btn btn-primary" href="#" id="bbb">{{ __('訂票') }}</a>
    @endif
</div>
@endsection

@section('js')
<script>
    $("#aaa").click(function(){
        location.href='{{ url("train_select") }}/'+$("#code").val();
        return false;
    })
    $("#bbb").click(function(){
        location.href='{{ url("booking") }}/'+$("#code").val();
        return false;
    })
</script>
@endsection
