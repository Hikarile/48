@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{ __('訂票查詢') }}</div>
    
    <form class="py-3" method="GET" action="{{ url('login/book') }}">
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
                        <option value="{{ $train->code }}">{{ $train->code }}</option>
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
                <select class="form-control" name="from" id="from">
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->chinese }}</option>
                    @endforeach
                </select>至
                <select class="form-control" name="to" id="to">
                    @foreach($stations as $station)
                        <option value="{{ $station->id }}">{{ $station->chinese }}</option>
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
        <tr class="bg-dark text-light">
            <th>訂票編號</th>
            <th>訂票時間</th>
            <th>發車時間</th>
            <th>車次</th>
            <th>起訖站</th>
            <th>張數</th>
            <th></th>
        </tr>
        @foreach($bookings as $book)
        <tr>
            <th>{{ $book->code }}</th>
            <th>{{ $book->created_at }}</th>
            <th>{{ $book->day }} {{ $book->time }}</th>
            <th>{{ $book->train_name }}</th>
            <th>{{ $stations->get($book->from-1)->chinese }}/{{ $stations->get($book->to-1)->chinese }}</th>
            <th>{{ $book->count }}</th>
            <th>
            @if( time() >  strtotime($book->day." ".$book->time) )
                以發車
            @else
                <button class="btn btn-danger" onclick="location.href='{{ url('select_delete').'/'.$book->id }}'">取消訂票</button>
            @endif  
            </th>
        </tr>
        @endforeach
    </table>
</div>
{{ $bookings->appends(request()->all())->links() }}

@endsection

