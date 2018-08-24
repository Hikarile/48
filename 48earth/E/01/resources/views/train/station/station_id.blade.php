@extends('start')
@section('text')

    @if($tf)
    <form action="{{ route('station_create') }}" method="post">
        <label>新增車站</label><p/>
        {{ csrf_field() }}
        車站名稱(中文):<input type="text" name="chinese"><p/>
        車站名稱(英文):<input type="text" name="english"><p/>
        <button>確定</button>
    </form>
    @else
    <form action="{{ route('station_create') }}" method="post">
        <label>修改車站</label><p/>
        {{ csrf_field() }}
        車站名稱(中文):<input type="text" name="chinese" value="{{ $chinese }}"><p/>
        車站名稱(英文):<input type="text" name="english" value="{{ $english }}"><p/>
        <input type="hidden" name="id" value="{{ $id }}">
        <button>確定</button>
    </form>
    @endif

@endsection
