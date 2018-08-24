@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('訂票查詢') }}</div>

                <div class="card-body">
                    <form method="GET" id="from">
                        @csrf

                        <div class="form-group row">
                            <label for="phone" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="number" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ request()->phone }}" required autofocus>

                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button station="submit" class="btn btn-primary">
                                    {{ __('訂票') }}
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
                        @foreach($booking as $book)
                        <tr>
                            <th>{{ $book->code }}</th>
                            <th>{{ $book->created_at }}</th>
                            <th>{{ $book->start_time }}</th>
                            <th>{{ $book->train_name }}</th>
                            <th>{{ $station->get($book->start)->chinese }}/{{ $station->get($book->end)->chinese }}</th>
                            <th>{{ $book->count }}</th>
                            <th>
                                @if($book->deleted_at != null)
                                    {{ $book->deleted_at }}
                                @else
                                    <button station="submit" class="btn btn-danger" onclick="location.href='{{ url('delete') }}/{{ $book->id }}'">{{ __('取消訂票') }}</button>
                                @endif
                            </th>
                        </tr>
                        @endforeach
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $("#from").submit(function(){
        location.href='{{ url("select") }}'+ '/' + $("#phone").val();
        return false;
    })
</script>
@endsection
