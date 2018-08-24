<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
	<id>{{ $album->album_id }}</id>
	<title>{{ $album->title }}</title>
    @if($album->description != '')
    <description>{{ $album->description }}</description>
    @else
    <description />
    @endif
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
    <images>{{ $album->images->count() }}</images>
    @if($album->images->count())
    <images>
        @foreach($album->images as $image)
        <item>
            <id>{{ $image->image_id }}</id>
            <title>{{ $image->title }}</title>
            @if($image->description != '')
            <description>{{ $image->description }}</description>
            @else
            <description />
            @endif
            <datetime>{{ $image->updated_at->timestamp }}</datetime>
            <width>{{ $image->width }}</width>
            <height>{{ $image->height }}</height>
            <size>{{ $image->size }}</size>
            <views>{{ $image->views }}</views>
            <link>http://127.0.0.1/05_Moudle_D/i/{{ $image->image_id }}.jpg</link>
        </item>
        @endforeach
    </images>
    @else
    <images />
    @endif
</data>