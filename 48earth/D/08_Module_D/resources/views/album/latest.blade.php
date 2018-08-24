<?xml version="1.0" encoding="utf-8"?>
<data type="string" success="1" status="200">
    <id>{{ $album->album_id }}</id>
    <title>{{ $album->title }}</title>
    @if($album->description != '')
        <description>{{ $album->description }}</description>
    @else
        </description>
    @endif

    @if($album->covers != '[]')
    <covers>
        @foreach(json_decode($album->covers, true) as $cover)
        <cover>{{ $cover }}</cover>
        @endforeach
    </covers>
    @else
    <covers />
    @endif
    <account>{{ $account->account_id }}</account>
    <link>http:\\127.0.0.1/08_Module_D/album{{ $album->album_id }}</link>
    <images_count>{{ $album->images->count() }}</images_count>
    @if($album->latest->count() != 0)
    <images>
    @foreach($album->latest as $image)
       <item>
            <id>{{ $image->image_id }}</id>
            <title>{{ $image->title }}</title>
            @if($image->description != '')
            <description>{{ $image->description }}</description>
            @else
            </description>
            @endif
            <width>{{ $image->width }}</width>
            <height>{{ $image->height }}</height>
            <size>{{ $image->size }}</size>
            <views>{{ $image->views }}</views>
            <link>http:\\127.0.0.1/08_Module_D/idate{{ $image->image_id }}.jpg</link>
        </item>
    @endforeach
    </images>
    @else
    <images />
    @endif
</data>