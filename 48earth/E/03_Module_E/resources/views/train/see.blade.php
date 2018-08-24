@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        <div class="card">
                <div class="card-header">{{ __('列車查詢') }}</div>

                <div class="card-body">
                    <form method="GET">
                        @csrf
                        
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('列車編號') }}</label>

                            <div class="col-md-4 form-inline">
                                <select class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required name="name" id="name">
                                    @foreach($trains as $train)
                                    <option value="{{ $train->name }}" {{ request()->name == $train->name?'selected' :'' }}>{{ $train->name }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
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

                    <table class="table table-hover text-center">
                        <tr class="bg-dark text-light">
                            <th>行駛星期</th>
                            <th>各車站抵達時間及發車時間</th>
                            <th>訂票</th>
                        </tr>
                        @foreach($gets as $train)
                        <tr>
                            <td>{{  $train->day }}</td>
                            <td>
                            @php
                            $time = date("Y-m-d").' '.$train->start_time;
                            @endphp
                                @foreach($train->stops as $key =>  $stop)
                                    {{ $stations->get($stop->station_id-1)->chinese }}站，
                                    @if($key == 0)
                                        抵達時間X分,
                                    @else
                                        抵達時間
                                        @php
                                            $time = date("Y-m-d H:i:s", strtotime($time ." + ".$stop->time."min"));
                                            echo explode(' ', $time)[1]; 
                                            $time = date("Y-m-d H:i:s", strtotime($time ." + ".$stop->stop_time."min"));
                                        @endphp
                                        分,
                                    @endif
                                    @if($key == $train->stops()->count()-1 )
                                        發車時間X分 
                                    @else
                                        發車時間
                                        @php
                                            echo explode(' ', $time)[1]; 
                                        @endphp
                                        分<br/>
                                    @endif
                                    
                                @endforeach
                            </td>
                            <td>
                            <button class="btn btn-success" onclick="location.href='{{ url('booking/'.$gets->get(0)->id).'///' }}'">訂票</button>
                            </td>
                        </tr>
                        @endforeach
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $('form').submit(function (event) {
        event.preventDefault();

        var name = $('#name').val();
        location.href = '{{ url("see") }}' + '/' + name;
    });
</script>
@endsection