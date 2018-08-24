@extends('layouts.app')

@section('content')
    <form class="py-3" method="GET" action="{{ url('login/booking') }}">

        <div class="form-group row">
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('發車日期') }}</label>

            <div class="col-md-3">
                <input class="form-control" name="day" id="day" type="date" type="day" value="{{ date('Y-m-d') }}" autofocus>
            </div>
        </div>

        <div class="form-group row">
            <label for="train_name" class="col-md-4 col-form-label text-md-right">{{ __('車次') }}</label>

            <div class="col-md-3">
                <select class="form-control" name="train_name" id="train_name">
                    @foreach($trains as $train)
                        <option value="{{ $train->name }}">{{ $train->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

            <div class="col-md-3">
                <input class="form-control" name="phone" id="phone" value="">
            </div>
        </div>

        <div class="form-group row">
            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

            <div class="col-md-3">
                <input class="form-control" name="code" id="code" type="" value="">
            </div>
        </div>

        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right">{{ __('起訖站') }}</label>

            <div class="col-md-3 form-inline">
                <select class="form-control" name="start" id="start">
                    @foreach($stations as $station)
                        <option value="{{ $station->id-1 }}">{{ $station->chinese }}</option>
                    @endforeach
                </select>至
                <select class="form-control" name="end" id="end">
                    @foreach($stations as $station)
                        <option value="{{ $station->id-1 }}">{{ $station->chinese }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-success">
                    {{ __('查詢') }}
                </button>
            </div>
        </div>

    </form>

    <table class="table table-hover text-center">
        <thead>
            <tr class="bg-secondary text-light">
                <th>訂票編號</th>
                <th>訂票時間</th>
                <th>發車時間</th>
                <th>車次</th>
                <th>起訖站</th>
                <th>張數</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($booking as $book)  
                <tr>
                    <td>{{ $book->code }}</td>
                    <td>{{ $book->created_at }}</td>
                    <td>{{ $book->day }} {{ $book->start_time }}</td>
                    <td>{{ $book->train_name }}</td>
                    <td>{{ $stations->get($book->start)->chinese . $stations->get($book->end)->chinese }}</td>
                    <td>{{ $book->count }}</td>
                    <td>
                        @if (time() >= strtotime($book->day . ' ' . $book->start_time))
                            已發車
                        @else
                            <button station="submit" class="btn btn-danger" onclick="location.href='{{ url('delete') }}/{{ $book->id }}'">{{ __('取消訂票') }}</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $booking->appends(request()->all())->links() }}
@endsection
