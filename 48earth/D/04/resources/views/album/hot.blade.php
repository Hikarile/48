<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    <id>{{ $album->album_id }}</id>
    <title>{{ $album->title }}</title>
    <description>{{ $album->description }}</description>
    @if( $album->cover != "[]" )
    <covers>
    @foreach(json_decode($album->cover, true) as $cover)
        <cover>{{ $cover }}</cover>
    @endforeach
    </covers>
    @else
    <covers />
    @endif
    <account>{{ $account->account_id }}</account>
    <link>http::\127.0.0.1\04_Module_D\{{ $album->album_id }}</link>
    <images_count>{{ $album->images->count() }}</images_count>
    @if( $album->hot->count())
    <images>
    @foreach($album->hot as $image)
        <item>
            <id>{{ $image->image_id }}</id>
            <title>{{ $image->title }}</title>
            <description>{{ $image->description }}</description>
            <width>{{ $image->width }}</width>
            <height>{{ $image->height }}</height>
            <size>{{ $image->size }}</size>
            <views>{{ $image->view }}</views>
            <link>http::\127.0.0.1\04_Module_D\i\{{ $image->image_id }}.jpg</link>
        </item>
    @endforeach
    </images>
    @else
    <images />
    @endif
</data>