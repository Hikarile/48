@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{  __('修改列車') }}</div>

<div class="card-body">
    <form method="POST" action="{{ url('login/train/create', $trains->id)}}">
        @csrf

        <div class="form-group row">
            <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('列車編號') }}</label>

            <div class="col-md-6">
                <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ $trains->code?:old('code') }}" required autofocus>

                @if ($errors->has('code'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('code') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="type_id" class="col-sm-4 col-form-label text-md-right">{{ __('車種') }}</label>

            <div class="col-md-6">
                <select name="type_id" id="type_id" class="form-control{{ $errors->has('type_id') ? ' is-invalid' : '' }}">
                    @foreach($types as $type)
                    <option value="{{ $type->id }}" {{ $trains->type_id == $type->id ? 'selected' : '' }} >{{ $type->name }}</option>
                    @endforeach
                </select>

                @if ($errors->has('type_id'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('type_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="day" class="col-sm-4 col-form-label text-md-right">{{ __('行車星期') }}</label>

            <div class="col-md-6">
                @foreach($days as $day)
                <input name="day[]" type="checkbox" value="{{ $day }}" {{ in_array($day, explode(',', $trains->day))? 'checked' :'' }} >
                <label>{{ $day }}</label>
                @endforeach

                @if ($errors->has('day'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('day') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="start_time" class="col-md-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

            <div class="col-md-6">
                <input id="start_time" type="time" class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}" name="start_time" value="{{ $trains->start_time?:old('start_time')?:date('H:i:s') }}" required>

                @if ($errors->has('start_time'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('start_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="car" class="col-md-4 col-form-label text-md-right">{{ __('車廂數量') }}</label>

            <div class="col-md-6">
                <input id="car" type="number" class="form-control{{ $errors->has('car') ? ' is-invalid' : '' }}" name="car" value="{{ $trains->car?:old('car') }}" required>

                @if ($errors->has('car'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('car') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="people" class="col-md-4 col-form-label text-md-right">{{ __('單一車廂數量') }}</label>

            <div class="col-md-6">
                <input id="people" type="number" class="form-control{{ $errors->has('people') ? ' is-invalid' : '' }}" name="people" value="{{$trains->people?: old('people') }}" required>

                @if ($errors->has('people'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('people') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="all" class="col-md-4 col-form-label text-md-right">{{ __('總載客數') }}</label>

            <div class="col-md-6">
                <input id="all" type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" name="all" value="{{ $trains->car*$trains->people }}" required disabled>

                @if ($errors->has('all'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('all') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="from" class="col-md-4 col-form-label text-md-right">{{ __('發車站') }}</label>

            <div class="col-md-6">
                <select name="from" id="from" class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->chinese }}" id="{{ $station->id }}" {{ $trains->stops->first()->station_id == $station->id?'selected':'' }} >{{ $station->chinese }}</option>
                    @endforeach
                </select>
                @if ($errors->has('from'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('from') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="to" class="col-md-4 col-form-label text-md-right">{{ __('終點站') }}</label>

            <div class="col-md-6">
                <select name="to" id="to" class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->chinese }}" id="{{ $station->id }}" {{ $trains->stops->last()->station_id == $station->id?'selected':'' }}>{{ $station->chinese }}</option>
                    @endforeach
                </select>
                @if ($errors->has('to'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('to') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <a class="btn btn-success" href="#" data-toggle="modal" data-target="#station_modal">新增行經車站</a>
        <table class="table table-hover table-center">
            <tr class="bg-dark text-light">
                <th>車站名稱</th>
                <th>停留時間(分)</th>
                <th>上一站到此站的行駛時間(分)</th>
                <th>上一站到此站的票價(元)</th>
                <th><th>
            </tr>
            <tr>
                <td>
                    <span id="from_name"></span>
                    <input name="station_id[]" id="from_id" hidden>
                </td>
                <td>
                    <input name="stop_time[]" type="number" value="0" hidden>
                </td>
                <td>
                    <input name="time[]" type="number" value="0" hidden>
                </td>
                <td>
                    <input name="money[]" type="number" value="0" hidden>
                </td>
            </tr>
            @foreach($trains->stops as $key => $val)
            @if($key != 0 && $key != $trains->stops->count()-1)
            <tr>
                <td>
                    <span>{{ $stations->get($val->station_id-1)->chinese }}</span>
                    <input class="form-control" name="station_id[]" value="{{ $val->station_id }}" hidden>
                </td>
                <td>
                    <input class="form-control" name="stop_time[]" type="number" value="{{ $val->stop_time }}" required>
                </td>
                <td>
                    <input class="form-control" name="time[]" type="number" value="{{ $val->time }}" required>
                </td>
                <td>
                    <input class="form-control" name="money[]" type="number" value="{{ $val->money }}" required>
                </td>
                <td>
                    <a href="#" class="btn btn-danger station_delete">X</a> 
                </td>
            </tr>
            @endif
            @endforeach
            <tr id="end">
                <td>
                    <span id="to_name">{{ $stations->get($trains->stops->last()->station_id-1)->chinese }}</span>
                    <input class="form-control" name="station_id[]" value="{{ $trains->stops->last()->station_id }}" id="to_id" hidden>
                </td>
                <td>
                    <input class="form-control" name="stop_time[]" type="number" value="{{ $trains->stops->last()->stop_time }}" required hidden>
                </td>
                <td>
                    <input class="form-control" name="time[]" type="number" value="{{ $trains->stops->last()->time }}" required>
                </td>
                <td>
                    <input class="form-control" name="money[]" type="number" value="{{ $trains->stops->last()->money }}" required>
                </td>
            </tr>
        </table>
        <div class="modal fade" id="station_modal"> 
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>新增行經車站</h3>
                    </div>
                    <div class="modal-body">
                    <select name="stations" id="stations" class="form-control{{ $errors->has('stations') ? ' is-invalid' : '' }}">
                        @foreach($stations as $station)
                        <option value="{{ $station->chinese }}" id="{{ $station->id }}">{{ $station->chinese }}</option>
                        @endforeach
                    </select>
                    </div>
                    <div class="modal-foot">
                        <a href="#" class="btn btn-primary" id="add_station">確定</a>
                        <a href="#" class="btn btn-success" data-dismiss="modal">取消</a>
                    </div>
                </div>
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
@endsection

@section('js')
    <script>
        $("#car, #people").on('input', function(){
            $("#all").val($("#car").val() * $("#people").val());
        });

        $("#from").change(function(){
            $("#from_name").text($(this).val());
            $("#from_id").val($("#from option:selected").attr('id'));
        }).change();
        $("#to").change(function(){
            $("#to_name").text($(this).val());
            $("#to_id").val($("#to option:selected").attr('id'));
        }).change();
        
        $(document).on('click', '.station_delete', function () {
            $(this).parents('tr').remove();
        });
        
        $("#add_station").click(function(){
            var station_name = $("#stations").val();
            var station_id = $("#stations option:selected").attr('id')
            $("#end").before(`
                <tr>
                    <td>
                        <span>${station_name}</span>
                        <input class="form-control" name="station_id[]" value="${station_id}" hidden>
                    </td>
                    <td>
                        <input class="form-control" name="stop_time[]" type="number" value="1" required>
                    </td>
                    <td>
                        <input class="form-control" name="time[]" type="number" value="20" required>
                    </td>
                    <td>
                        <input class="form-control" name="money[]" type="number" value="100" required>
                    </td>
                    <td>
                        <a href="#" class="btn btn-danger station_delete">X</a>
                    </td>
                </tr>
            `);
            $('#station_modal').modal('hide');
        })

    </script>
@endsection
