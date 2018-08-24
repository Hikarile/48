@extends('start')
@section('text')
    <form action="{{ route('login_confirm') }}" method="post">
        {{ csrf_field() }}
        帳號:<input type="text" id="ac" name="ac" value="{{ old('ac') }}"><br/>
        密碼:<input type="password" id="ps" name="ps" value=""><br/>
        <button>確定</button>
    </form>
@endsection