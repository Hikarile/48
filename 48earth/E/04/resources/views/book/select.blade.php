@extends('layouts.app')

@section('css')
@endsection

@section('content')
<div class="card-header">{{ __('訂票查詢') }}</div>
    
<div class="form-group row">
        <label for="code" class="col-md-4 col-form-label text-md-right">{{ __('訂票編號') }}</label>

        <div class="col-md-6">
            <input id="code" type="text" class="form-control" name="code" value="">
        </div>
    </div>

    <div class="form-group row">
        <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

        <div class="col-md-6">
            <input id="phone" type="text" class="form-control" name="phone" value="">
        </div>
    </div>
    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button class="btn btn-success" id="ok">確定</button>
        </div>
    </div>
    

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
            <th>{{ $book->time }}</th>
            <th>{{ $book->train_name }}</th>
            <th>{{ $stations->get($book->from-1)->chinese }}/{{ $stations->get($book->to-1)->chinese }}</th>
            <th>{{ $book->count }}</th>
            <th>
            @if( $book->deleted_at == null )
                <button class="btn btn-danger" onclick="location.href='{{ url('select_delete').'/'.$book->id }}'">取消訂票</button>
            @else
                {{  $book->deleted_at }}
            @endif  
            </th>
        </tr>
        @endforeach
    </table>
</div>

@endsection

@section('js')
    <script>
        $("#ok").click(function(){
            var phone = $("#phone").val();
            var code = $("#code").val();
            if(phone == ''){
                phone = 'null';
            }
            if(code == ''){
                code = 'null';
            }
            location.href='{{ url("select") }}/'+phone+"/"+code;
        })
    </script>
@endsection
