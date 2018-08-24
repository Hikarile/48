<?xml version="1.0" encoding="utf-8"?>
<data type="string" success="1" status="200">
    <account>{{ $account->account_id }}</account>
    <bio>{{ $account->bio }}</bio>
    <create>{{ $account->updated_at->timestamp }}</create>
    @if($account->albums->count() != 0)
    <albums>
    @foreach($account->albums as $album)
    <album id="{{ $album->album_id }}" count="{{ $album->images->count() }}"></album>
    @endforeach
    </albums>
    @else
    <albums />
    @endif
</data>