@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('訂票查詢') }}</div>

<div class="card-body">
    <form method="GET" action="{{ url('login/book') }}">
        <div class="form-group row">
            <label for="type_id" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

            <div class="col-md-6">
                <input name="phone" id="phone" type="text" value="{{ request()->phone }}" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

            <div class="col-md-6">
                <input name="code" id="code" type="text" value="{{ request()->code }}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="day" class="col-md-4 col-form-label text-md-right">{{ __('發車日期') }}</label>

            <div class="col-md-6">
                <input name="day" id="day" type="date" value="{{ request()->day?:date('Y-m-d') }}" class="form-control{{ $errors->has('day') ? ' is-invalid' : '' }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="train_name" class="col-sm-4 col-form-label text-md-right">{{ __('車次代碼') }}</label>

            <div class="col-md-6">
                <select name="train_name" id="train_name" class="form-control{{ $errors->has('train_name') ? ' is-invalid' : '' }}">
                    @foreach($trains as $train)
                    <option value="{{ $train->code }}" {{ request()->train_name == $train->name?'selected':'' }}>{{ $train->code }}</option>
                    @endforeach
                </select>

                @if ($errors->has('train_name'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('train_name') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('起程站') }}</label>

            <div class="col-md-6">
                <select name="from" id="from" class="form-control{{ $errors->has('from') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}"  {{ request()->from == $station->id?'selected':''  }}>{{ $station->chinese }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('到達站') }}</label>

            <div class="col-md-6">
                <select name="to" id="to" class="form-control{{ $errors->has('to') ? ' is-invalid' : '' }}">
                    @foreach($stations as $station)
                    <option value="{{ $station->id }}"  {{ request()->to == $station->id?'selected':''  }}>{{ $station->chinese }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button class="btn btn-primary">
                    {{ __('確定') }}
                </button>
            </div>
        </div>
    </form>
    <table class="table text-center table-hover">
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
                    @if($book->deleted_at == '')
                        <button class="btn btn-danger" onclick="location.href='{{ url('book_delete').'/'.$book->id }}'">{{ __('取消訂票') }}</button>
                    @else
                        {{ $book->deleted_at }}
                    @endif
                @else
                    以發車
                @endif
            </th>
        </tr>
        @endforeach
    </table>
    {{ $books->appends(request()->all())->links() }}
</div>
@endsection

@section('js')
@endsection
