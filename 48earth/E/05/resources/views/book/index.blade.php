@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{  __('修改車種') }}</div>

<div class="card-body">
    <form class="py-3" method="GET">
        @csrf
        
        <div class="form-group row">
            <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('車次代碼') }}</label>

            <div class="col-md-6">
                <select name="code" id="code" class="form-control">
                    @foreach($trains as $train)
                    <option value="{{ $train->code }}" {{ request()->day?:request()->type == $train->code?'selected':'' }} >{{ $train->code }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

            <div class="col-md-6">
                <input id="phone" type="text" class="form-control" name="phone" value="{{ request()->phone }}">

                @if ($errors->has('phone'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('搭乘日期') }}</label>

            <div class="col-md-6">
                <input id="day" type="date" class="form-control" name="day" value="{{ request()->day?:date('Y-m-d') }}" required autofocus>
            </div>
        </div>
        <div class="form-group row">
            <label for="code" class="col-sm-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

            <div class="col-md-6">
                <input id="code" type="text" class="form-control" name="code" value="{{ request()->code }}">

                @if ($errors->has('code'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('code') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="from" class="col-sm-4 col-form-label text-md-right">{{ __('起程站') }}</label>

            <div class="col-md-6">
                <select name="from" id="from" class="form-control">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ request()->from == $station->english?'selected':'' }}>{{ $station->chinese }}</option>
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
            <label for="to" class="col-sm-4 col-form-label text-md-right">{{ __('到達站') }}</label>

            <div class="col-md-6">
                <select name="to" id="to" class="form-control">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}" {{ request()->to == $station->english?'selected':'' }}>{{ $station->chinese }}</option>
                    @endforeach
                </select>

                @if ($errors->has('to'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('to') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button id="aa" class="btn btn-primary">
                    {{ __('確定') }}
                </button>
            </div>
        </div>
    </form>

    <table class="table table-hover text-center">
        <tr class="bg-dark text-light">
            <th>訂票編號</th>
            <th>訂票時間</th>
            <th>發車時間</th>
            <th>車次</th>
            <th>起訖站</th>
            <th>張數</th>
            <th></th>
        </tr>
        @foreach($books as $book)
        <tr>
            <th>{{ $book->code }}</th>
            <th>{{ $book->created_at }}</th>
            <th>{{ $book->day }} {{ $book->time }}</th>
            <th>{{ $book->train_name }}</th>
            <th>{{ $stations->get($book->from-1)->chinese }}/{{ $stations->get($book->to-1)->chinese }}</th>
            <th>{{ $book->count }}</th>
            <th>
                @if(time() < strtotime($book->day.$book->time) )
                <button class="btn btn-danger" onclick="location.href='{{ url('book_delete').'/'.$book->id }}'">{{ __('取消訂票') }}</button>
                @else
                以發車
                @endif
            </th>
        </tr>
        @endforeach
    </table>

</div>
@endsection

@section('js')
<script>
</script>
@endsection
