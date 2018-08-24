@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ request()->id == '' ?  __('新增列車')  :  __('修改列車')  }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ url('login/train/create', $trains->id)}}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-sm-4 col-form-label text-md-right">{{ __(' 列車代碼') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="name" type="number" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') ?:  $trains->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}站</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="type_id" class="col-md-4 col-form-label text-md-right">{{ __('列車車種') }}</label>

                            <div class="col-md-4 form-inline">

                                <select class="form-control{{ $errors->has('type_id') ? ' is-invalid' : '' }}" required name="type_id" id="type_id">
                                    @foreach($types as $type)
                                    <option value="{{ $type->id }}"  {{ $trains->type == $type->id ? selected : '' }}>{{ $type->name }}</option>
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
                                        <input name="day[]" type="checkbox" value="{{ $day }}" {{ in_array($day, explode(',', $trains->day)) ? 'checked' : '' }}>
                                        <label>{{ $day }}</label>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="start_time" class="col-sm-4 col-form-label text-md-right">{{ __(' 發車時間') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="start_time" type="time" class="form-control{{ $errors->has('start_time') ? ' is-invalid' : '' }}" min="0" name="start_time" value="{{ old('start_time') ?: $trains->start_time }}" required autofocus>

                                @if ($errors->has('start_time'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('start_time') }}站</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="people" class="col-md-4 col-form-label text-md-right">{{ __('單一車廂在載客數') }}</label>

                            <div class="col-md-4 form-inline">
                                <input id="people" type="number" class="form-control{{ $errors->has('people') ? ' is-invalid' : '' }}" name="people" value="{{ old('people') ?: $trains->people }}" required autofocus>

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
                                <input id="number" type="number" class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}" name="number" value="{{ old('number') ?: $trains->number }}" required autofocus>

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
                                <input id="all_people" type="number" class="form-control{{ $errors->has('all_people') ? ' is-invalid' : '' }}" name="all_people" value="{{ $trains->people*$trains->number }}" required disabled>

                                @if ($errors->has('all_people'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('all_people') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="from" class="col-md-4 col-form-label text-md-right">{{ __('發車站') }}</label>

                            <div class="col-md-4 form-inline">

                                <select class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}" required name="from" id="from">
                                    @foreach($stations as $station)
                                    <option value="{{ $station->chinese }}" station_id="{{ $station->id }}" {{ $trains->id && $station->id == $trains->stops->first()->station_id ? ' selected' : '' }}>{{ $station->chinese }}站</option>
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

                            <div class="col-md-4 form-inline">

                                <select class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}" required name="to" id="to">
                                    @foreach($stations as $station)
                                    <option value="{{ $station->chinese }}" station_id="{{ $station->id }}" {{ $trains->id && $station->id == $trains->stops->last()->station_id ? ' selected' : '' }}>{{ $station->chinese }}站</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('to'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('to') }}</strong>
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
                                    <span id="from_start_span">{{ $stations[0]->chinese  }}</span>
                                    <input id="from_start_input" name="stop_station[]" value="{{ $stations[0]->id }}" hidden>
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
                            
                            @foreach($trains->stops as $key => $val)
                            @if($key > 0 && $key < $trains->stops()->count()-1)
                            <tr>
                                <td>
                                    <span id="from_start_span">{{ $stations->get($val->station_id-1)->chinese }}</span>
                                    <input id="from_start_input" name="stop_station[]" value="{{ $val->station_id }}" hidden>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input class="form-control" name="stop_time[]" type="number" value="{{ $val->time }}" required>
                                        <span>分</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input class="form-control" name="stop_stop_time[]" type="number" value="{{ $val->stop_time }}" required>
                                        <span>分</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input class="form-control" name="stop_money[]" type="number" value="{{ $val->money }}" required>
                                        <span>元</span>
                                    </div>
                                </td>
                                <td>
                                @if( $trains->stops()->count()-1 != $key )
                                <div class="form-inline">
                                    <button class="btn-danger station_delete">X</button>
                                </div>
                                @endif
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            
                            <tr id="to_station">
                                <td>
                                    <span id="to_start_span">{{ $stations->get($trains->stops()->count()-1)->chinese  }}</span>
                                    <input id="to_start_input" name="stop_station[]" value="{{ $stations[$trains->stops()->count()-1]->id }}" hidden>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input class="form-control" name="stop_time[]" type="number" value="{{ $trains->stops[$trains->stops()->count()-1]->time }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input class="form-control" name="stop_stop_time[]" type="number" value="{{ $trains->stops[$trains->stops()->count()-1]->stop_time }}" required>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-inline">
                                        <input class="form-control" name="stop_money[]" type="number" value="{{ $trains->stops[$trains->stops()->count()-1]->money }}" required>
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

    $("#from").change(function(){
        $("#from_start_span").text($(this).val());
        $("#from_start_input").val($("#from option:selected").attr('station_id'));
    }).change();

    $("#to").change(function(){
        $("#to_start_span").text($(this).val());
        $("#to_start_input").val($("#to option:selected").attr('station_id'));
    }).change();

    $(document).on('click', '.station_delete', function () {
        $(this).parentsUntil('tbody').remove();
    });
    
    $('#add_station').click(function () {
        let station_id = $('#station_id').val();
        let station_chinese = $('#station_id option:selected').text();
        
        $('#to_station').before(`
            <tr>
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