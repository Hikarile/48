@extends('start')
@section('text')
    <button onclick="location.href='{{ route('type') }}'">返回</button><p/>

    @if($tf)
        <div id="type_add">
            <form action="{{ route('type_create') }}" method="post">
                {{ csrf_field() }}
                <label>新增車種</label><br/>
                車種名稱:<input type="text" id="type" name="type"><p/>
                時速(km/hr):<input type="text" id="speed" name="speed"><br/>
                <input type="submit" name="ok" id="ok" value="確定">
            </form>
        </div>
    @else
        <div id="type_add">
            <form action="{{ route('type_create') }}" method="post">
                {{ csrf_field() }}
                <label>修改車種</label><br/>
                車種名稱:<input type="text" id="type" name="type" value="{{ $type }}"><p/>
                時速(km/hr):<input type="text" id="speed" name="speed" value="{{ $speed }}"><br/>
                <input type="hidden" id="id" name="id" value="{{ $id }}"><br/>
                <button>確定</button>
            </form>
        </div>
    @endif
@endsection
