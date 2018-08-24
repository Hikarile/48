<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
	<account>{{ $account->account }}</account>
	<bio>{{ $account->bio }}</bio>
    <created>{{ $account->updated_at->timestamp }}</created>
	@if($account->albums->count())
    <albums>
        @foreach($account->albums as $album)
        <album>{{ $album->album_id }}</album>
        @endforeach
    </albums>
    @else
    <albums />
    @endif
</data>