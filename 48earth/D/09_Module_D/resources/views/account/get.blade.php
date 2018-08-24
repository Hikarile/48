<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    <account>{{ $account->account }}</account>    
    <bio>{{ $account->bio }}</bio>    
    <create>{{ $account->created_at->timestamp }}</create>    
    @if($account->albums()->count())
        <albums>
            @foreach($account->albums as $album)
            <album id="{{ $album->album_id }}" count="{{ $album->images->count() }}"></album>
            @endforeach
        </albums>
    @else
        <albums />
    @endif
</data>