@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('訂票查詢') }}</div>

<div class="card-body">
    <form method="GET" action="{{ url('book/select') }}">
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

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button class="btn btn-primary">
                    {{ __('確定') }}
                </button>
            </div>
        </div>

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
        @if($tf)
        @foreach($books as $book)
        <tr>
            <th>{{ $book->code }}</th>
            <th>{{ $book->created_at }}</th>
            <th>{{ $book->day }} {{ $book->time }}</th>
            <th>{{ $book->train_name }}</th>
            <th>{{ $stations->get($book->from-1)->chinese }}/{{ $stations->get($book->to-1)->chinese }}</th>
            <th>{{ $book->count }}</th>
            <th>
                <button class="btn btn-danger" onclick="location.href='{{ url('book/select/delete').'/'.$book->id }}'">{{ __('取消訂票') }}</button>
            </th>
        </tr>
        @endforeach
        @endif
    </table>
    </form>

</div>
@endsection

@section('js')
@endsection
