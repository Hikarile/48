@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{  __('修改車種') }}</div>

<div class="card-body">
    <form method="GET">
        @csrf
        
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
                <button class="btn btn-danger" onclick="location.href='{{ url('book_delete').'/'.$book->id }}'">{{ __('取消訂票') }}</button>
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
