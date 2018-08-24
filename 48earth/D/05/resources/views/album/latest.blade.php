<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
	<id>{{ $album->album_id }}</id>
	<title>{{ $album->title }}</title>
    <description>{{ $album->description }}</description>
    <datetime>{{ $album->updated_at->timestamp }}</datetime>
	@if($album->covers != "[]")
    <covers>
        @foreach(json_decode($album->covers, true) as $cover)
        <cover>{{ $cover }}</cover>
        @endforeach
    </covers>
    @else
    <covers />
    @endif
    <account>{{ $account->account_id }}</account>
    <link>http://127.0.0.1/05_Noudle_D/{{ $album->album_id }}</link>
    <images>{{ $album->latest->count() }}</images>
    @if($album->latest->count())
    <images>
        @foreach($album->latest as $image)
        <item>
            <id>{{ $image->image_id }}</id>
            <title>{{ $image->title }}</title>
            <description>{{ $image->description }}</description>
            <width>{{ $image->width }}</width>
            <height>{{ $image->height }}</height>
            <size>{{ $image->size }}</size>
            <views>{{ $image->views }}</views>
            <link>http://127.0.0.1/05_Noudle_D/i/{{ $image->image_id }}.jpg</link>
        </item>
        @endforeach
    </images>
    @else
    <images />
    @endif
</data>