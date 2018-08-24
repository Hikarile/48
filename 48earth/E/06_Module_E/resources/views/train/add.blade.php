@extends('layouts.app')

@section('css')

@endsection
@section('content')
<div class="card-header">{{  __('新增列車') }}</div>

<div class="card-body">
    <form method="POST" action="{{ url('login/train/create') }}">
        @csrf

        <div class="form-group row">
            <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('列車代碼') }}</label>

            <div class="col-md-6">
                <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ old('code') }}" required autofocus>

                @if ($errors->has('code'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('code') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="type_id" class="col-md-4 col-form-label text-md-right">{{ __('車種') }}</label>

            <div class="col-md-6">
                <select name="type_id" id="type_id" class="form-control{{ $errors->has('type_id') ? ' is-invalid' : '' }}">
                    @foreach($types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
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
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('行車星期') }}</label>

            <div class="col-md-6">
                @foreach($days as $day)
                <span>{{ $day }}</span>
                <input id="day" type="checkbox" name="day[]" value="{{ $day }}" >
                @endforeach

                @if ($errors->has('day'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('day') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="start_time" class="col-sm-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

            <div class="col-md-6">
                <input id="start_time" type="time" class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}" name="start_time" value="{{ date('H:i:s') }}" required autofocus>

                @if ($errors->has('start_time'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('start_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="people" class="col-sm-4 col-form-label text-md-right">{{ __('單一車廂載客數') }}</label>

            <div class="col-md-6">
                <input id="people" type="number" class="form-control{{ $errors->has('people') ? ' is-invalid' : '' }}" name="people" value="{{ old('people') }}" required autofocus>

                @if ($errors->has('people'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('people') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="car" class="col-sm-4 col-form-label text-md-right">{{ __('車廂數量') }}</label>

            <div class="col-md-6">
                <input id="car" type="number" class="form-control{{ $errors->has('car') ? ' is-invalid' : '' }}" name="car" value="{{ old('car') }}" required autofocus>

                @if ($errors->has('car'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('car') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="all" class="col-sm-4 col-form-label text-md-right">{{ __('總載客數') }}</label>

            <div class="col-md-6">
                <input id="all" type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" name="all" value="{{ old('all') }}" required autofocus readonly>

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
                    <option value="{{ $station->chinese }}" d="{{ $station->id }}">{{ $station->chinese }}</option>
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
                    <option value="{{ $station->chinese }}" d="{{ $station->id }}">{{ $station->chinese }}</option>
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
        <table class="table table-hover text-center">
            <tr class="bg-dark text-light">
                <th>行經車站</th>
                <th>停留時間</th>
                <th>上一站到此站的行駛時間</th>
                <th>上一站到此站的票價</th>
                <th></th>
            </tr>
            <tr>
                <th>
                    <span id="from_name"></span>
                    <input id="from_id" type="text" name="station_id[]" value="" hidden>
                </th>
                <th><input type="text" name="stop_time[]" value="0" hidden></th>
                <th><input type="text" name="time[]" value="0" hidden></th>
                <th><input type="text" name="money[]" value="0" hidden></th>
            </tr>
            <tr id="end">
                <th>
                    <span id="to_name"></span>
                    <input id="to_id" type="text" name="station_id[]" value="" hidden>
                </th>
                <th><input type="text" name="stop_time[]" value="5" required></th>
                <th><input type="text" name="time[]" value="10" required></th>
                <th><input type="text" name="money[]" value="100" required></th>
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
                        <option value="{{ $station->chinese }}" d="{{ $station->id }}">{{ $station->chinese }}</option>
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
        $("#all").val($("#car").val()*$("#people").val());
    })
    $("#from").change(function(){
        $("#from_name").text($(this).val());
        $("#from_id").val($("#from option:selected").attr('d'));
    }).change();
    $("#to").change(function(){
        $("#to_name").text($(this).val());
        $("#to_id").val($("#to option:selected").attr('d'));
    }).change();

    $("#add_station").click(function(){
        var name = $("#stations").val();
        var id = $("#stations option:selected").attr('d');
        $("#end").before(`
            <tr>
                <th>
                    <span>${name}</span>
                    <input type="text" name="station_id[]" value="${id}" hidden>
                </th>
                <th><input type="text" name="stop_time[]" value="5" required></th>
                <th><input type="text" name="time[]" value="10" required></th>
                <th><input type="text" name="money[]" value="100" required></th>
                <th>
                    <a class="btn btn-danger delete" href="#">刪除</a>
                </th>
            </tr>
        `)
        $('#station_modal').modal('hide');
    })

    $(document).on('click', '.delete', function () {
        $(this).parents('tr').remove();
    });

</script>
@endsection
