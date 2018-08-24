@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('新增車種') }}</div>

<div class="card-body">
    <form method="POST" action="{{ url('login/train/create') }}">
        @csrf

        <div class="form-group row">
            <label for="type_id" class="col-sm-4 col-form-label text-md-right">{{ __('車種') }}</label>

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
            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('列車編號') }}</label>

            <div class="col-md-6">
                <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ old('code') }}" name="code" required>

                @if ($errors->has('code'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('code') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('行車星期') }}</label>

            <div class="col-md-6">
                @foreach($days as $day)
                    <span>{{ $day }}</span>
                    <input id="day" type="checkbox" value="{{ $day }}" name="day[]">
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
                <input id="start_time" type="time" class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}" step="1" value="{{ date('H:i:s') }}" step name="start_time" required>

                @if ($errors->has('start_time'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('start_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="people" class="col-md-4 col-form-label text-md-right">{{ __('單一車廂的載客數量') }}</label>

            <div class="col-md-6">
                <input id="people" type="number" class="form-control{{ $errors->has('people') ? ' is-invalid' : '' }}" min="1" value="{{ old('people') }}" name="people" required>

                @if ($errors->has('people'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('people') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="car" class="col-md-4 col-form-label text-md-right">{{ __('車廂數量') }}</label>

            <div class="col-md-6">
                <input id="car" type="number" class="form-control{{ $errors->has('car') ? ' is-invalid' : '' }}" min="1" value="{{ old('car') }}" name="car" required>

                @if ($errors->has('car'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('car') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="all" class="col-md-4 col-form-label text-md-right">{{ __('總載客數') }}</label>

            <div class="col-md-6">
                <input id="all" type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="{{ old('car')*old('people') }}" name="all" required readonly>

                @if ($errors->has('all'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('all') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="from" class="col-sm-4 col-form-label text-md-right">{{ __('起始站') }}</label>

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
            <label for="to" class="col-sm-4 col-form-label text-md-right">{{ __('終點站') }}</label>

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

        <a href="#" class="btn btn-success" data-toggle="modal" data-target="#aaa">新增行經車站</a>
        <div id="aaa" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>新增行經車站</h3>
                    </div>
                    <div class="modal-header">
                        <select name="station" id="station" class="form-control{{ $errors->has('station') ? ' is-invalid' : '' }}">
                            @foreach($stations as $station)
                            <option value="{{ $station->chinese }}" d="{{ $station->id }}">{{ $station->chinese }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-header">
                        <a href="#" class="btn btn-primary" id="add">新增</a>
                        <a href="#" class="btn btn-success" data-dismiss="modal">取消</a>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-hover text-center">
            <tr class="bg-dark text-light">
                <th>車站名稱</th>
                <th>停站時間</th>
                <th>上一站到該站的行使時間</th>
                <th>上一站到該站的票價</th>
                <th></th>
            </tr>
            <tr>
                <th>
                    <span id="from_name"></span>
                    <input id="from_id" type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="" name="station_id[]" required hidden>
                </th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="0" name="stop_time[]" required hidden></th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="0" name="time[]" required hidden></th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="0" name="money[]" required hidden></th>
                <th></th>
            </tr>
            <tr id="end">
                <th>
                    <span id="to_name"></span>
                    <input id="to_id" type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="" name="station_id[]" required hidden>
                </th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="1" name="stop_time[]" required></th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="10" name="time[]" required></th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="100" name="money[]" required></th>
                <th></th>
            </tr>
        </table>

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
        $("#from_name").text($("#from").val());
        $("#from_id").val($("#from option:selected").attr('d'));
    }).change();
    $("#to").change(function(){
        $("#to_name").text($("#to").val());
        $("#to_id").val($("#to option:selected").attr('d'));
    }).change();

    $("#add").click(function(){
        var name = $("#station").val();
        var id = $("#station option:selected").attr('d');
        $("#end").before(`
            <tr>
                <th>
                    <span>${name}</span>
                    <input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="${id}" name="station_id[]" required hidden>
                </th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="1" name="stop_time[]" required></th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="10" name="time[]" required></th>
                <th><input type="number" class="form-control{{ $errors->has('all') ? ' is-invalid' : '' }}" value="100" name="money[]" required></th>
                <th><a href="#" class="btn btn-danger delete">X</a></th>
            </tr>
        `)
        $("#aaa").modal('hide');
    })

    $(document).on('click', '.delete', function(){
        $(this).parents('tr').remove();
    })
</script>
@endsection
