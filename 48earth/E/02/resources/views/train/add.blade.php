@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">@if($train->id == ''){{ __('新增列車') }} @else{{ __('修改列車') }}@endif</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('login/train/create', $train->id)}}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __('列車代碼') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') ?: $train->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="type_id" class="col-md-4 col-form-label text-md-right">{{ __('列車車種') }}</label>

                            <div class="col-md-4 form-inline">
                                <select class="form-control{{ $errors->has('type_id') ? ' is-invalid' : '' }}" required name="type_id" id="type_id">
                                    @foreach($types as $type)
                                    <option value="{{ $type->id }}" >{{ $type->name }}</option>
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

                            <div class="col-md-4 form-inline">
                                @foreach($days as $key => $day)
                                    @if($key > 0)
                                    <div class="form-check form-check-inline">
                                        <input name="day[]" type="checkbox" value="{{ $key }}">
                                        <label>{{ $day }}</label>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="start_time" class="col-sm-4 col-form-label text-md-right">{{ __('發車時間') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="start_time" type="time" class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}" name="start_time" value="{{ old('start_time') ?: $train->start_time ?: date('H:i:s') }}" required autofocus>

                                @if ($errors->has('start_time'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('start_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="people" class="col-md-4 col-form-label text-md-right">{{ __('單一車廂在載客數') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="people" type="number" class="form-control{{ $errors->has('people') ? ' is-invalid' : '' }}" name="people" value="{{ old('people') ?: $train->people }}" required autofocus>

                                @if ($errors->has('people'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('people') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="number" class="col-md-4 col-form-label text-md-right">{{ __('車廂數量') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="number" type="number" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" name="number" value="{{ old('number') ?: $train->number }}" required autofocus>

                                @if ($errors->has('number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="all_people" class="col-md-4 col-form-label text-md-right">{{ __('總載客數') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="all_people" type="number" class="form-control{{ $errors->has('all_people') ? ' is-invalid' : '' }}" name="all_people" value="{{ $train->number * $train->people }}" required disabled>

                                @if ($errors->has('all_people'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('all_people') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="from_station_id" class="col-sm-4 col-form-label text-md-right">{{ __('發車站') }}</label>

                            <div class="col-md-4">
                                <select class="form-control{{ $errors->has('from_station_id') ? ' is-invalid' : '' }}" name="from_station_id" id="from_station_id" required>
                                    @foreach ($stations as $station)
                                    <option value="{{ $station->id }}" station_name="{{ $station->chinese }}" {{ $train->id && $station->id == $train->stops->first()->station_id ? ' selected' : '' }}>{{ $station->chinese }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('from_station_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('from_station_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="to_station_id" class="col-sm-4 col-form-label text-md-right">{{ __('終點站') }}</label>

                            <div class="col-md-4">
                                <select class="form-control{{ $errors->has('to_station_id') ? ' is-invalid' : '' }}" name="to_station_id" id="to_station_id" required>
                                    @foreach ($stations as $station)
                                    <option value="{{ $station->id }}" station_name="{{ $station->chinese }}" {{ $train->id && $station->id == $train->stops->last()->station_id ? ' selected' : '' }}>{{ $station->chinese }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('to_station_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('to_station_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="py-2 col-md-4 text-md-right">
                            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#station_modal">新增行經車站</a>
                        </div>
                        <table class="table table-hover text-center">
                            <tr class="bg-dark text-light">
                                <th>車站名稱</th>
                                <th>停留時間</th>
                                <th>與前一站到該站的行駛時間</th>
                                <th>與前一站到該站的票價</th>
                                <th></th>
                            </tr>
                            <tbody>
                                <tr>
                                    <td>
                                        <span id="from_start_span"></span>
                                        <input id="from_start_input" name="stop_station[]" value="" hidden>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input class="form-control" name="stop_time[]" type="number" value="0" required hidden>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input class="form-control" name="stop_stop_time[]" type="number" value="0" required hidden>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input class="form-control" name="stop_money[]" type="number" value="0" required hidden>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="to_station">
                                    <td>
                                        <span id="to_start_span"></span>
                                        <input id="to_start_input" name="stop_station[]" value="" hidden>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input class="form-control" name="stop_time[]" type="number" value="10" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input class="form-control" name="stop_stop_time[]" type="number" value="0" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-inline">
                                            <input class="form-control" name="stop_money[]" type="number" value="0" required>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
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

                <div class="modal fade" id="station_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>新增行經車站</h3>
                            </div>
                            <div class="modal-body">
                                <select class="form-control" id="station_id">
                                    @foreach ($stations as $station)
                                    <option value="{{ $station->id }}">{{ $station->chinese }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-success" id="add_station" href="#">新增</a>
                                <a class="btn btn-secondary"  href="#" data-dismiss="modal">取消</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $('#people, #number').on('input', function () {
        $('#all_people').val(parseInt($('#people').val()) * parseInt($('#number').val()));
    });

    $("#from_station_id").change(function(){
        $("#from_start_span").text($("#from_station_id option:selected").attr('station_name'));
        $("#from_start_input").val($(this).val());
    }).change();

    $("#to_station_id").change(function(){
        $("#to_start_span").text($("#to_station_id option:selected").attr('station_name'));
        $("#to_start_input").val($(this).val());
    }).change();

    $(document).on('click', '.station_delete', function () {
        $(this).parentsUntil('tbody').remove();
    });

    $('#add_station').click(function () {
        let station_id = $('#station_id').val();
        let station_chinese = $('#station_id option:selected').text();
        
        $('#to_station').before(`
            <tr id="to_station">
                <td>
                    <span>${station_chinese}</span>
                    <input name="stop_station[]" value="${station_id}" hidden>
                </td>
                <td>
                    <div class="form-inline">
                        <input class="form-control" name="stop_time[]" type="number" value="10" required>
                        <span>分鐘</span>
                    </div>
                </td>
                <td>
                    <div class="form-inline">
                        <input class="form-control" name="stop_stop_time[]" type="number" value="0" required>
                        <span>分鐘</span>
                    </div>
                </td> 
                <td>
                    <div class="form-inline">
                        <input class="form-control" name="stop_money[]" type="number" value="0" required>
                        <span>元</span>
                    </div>
                </td>
                <td>
                    <div class="form-inline">
                        <button class="btn-danger station_delete">X</button>
                    </div>
                </td>
            </tr>
        `);

        $('#station_modal').modal('hide');
    });

</script>
@endsection