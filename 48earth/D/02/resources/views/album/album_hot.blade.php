<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    <id>{{ $album->album_id }}</id>
    <title>{{ $album->title }}</title>
    <description>{{ $album->description }}</description>
    <datetime>{{ $album->updated_at->timestamp }}</datetime>
    @if( $album->images_cover->count() )
    <covers>
        @foreach($album->images_cover as $image)
        <cover>{{$image->image_id }}</cover>
        @endforeach
    </covers>
    @else
    <covers />
    @endif
    <link>http://127.0.0.1/php/48earth/D/02/album/{{ $album->album_id }}</link>
    <image_count>{{ $album->count }}</image_count>
    @if( $album->images_hots->count() )
    <images>
        @foreach($album->images_hots as $image)
        <item>
            <id>{{ $image->image_id }}</id>
            <title>{{ $image->title }}</title>
            <description>{{ $image->description }}</description>
            <width>{{ $image->width }}</width>
            <height>{{ $image->height }}</height>
            <size>{{ $image->size }}</size>
            <views>{{ $image->view }}</views>
            <link>http://127.0.0.1/php/48earth/D/02/i/{{ $image->image_id }}</link>
        </item>
        @endforeach
    </images>
    @else
    <images />
    @endif
</data>