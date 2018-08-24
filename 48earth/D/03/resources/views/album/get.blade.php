<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
    <id>{{ $album->album_id }}</id>
    <title>{{ $album->title }}</title>
    @if( $album->description != '')
    <description>{{ $album->description }}</description>
    @else
    <description />
    @endif
    @if ($album->getCovers()->count())
        <covers>
            @foreach ($album->getCovers() as $cover)
                <cover>{{ $cover->image_id }}</cover>
            @endforeach
        </covers>
    @else
        <covers />
    @endif
    <account>{{ $account->account_id }}</account>
    <link>http::/172.0.0.1/03_Modeule_F/{{ $album->album_id }}</link>
    <images_count>{{ $album->images()->count() }}</images_count>
    @if ($album->images()->count())
        <images>
            @foreach ($album->images as $image)
                <item>
                    <id>{{ $image->image_id }}</id>
                    <title>{{ $image->title }}</title>
                    <description>{{ $image->description }}</description>
                    <width>{{ $image->width }}</width>
                    <height>{{ $image->height }}</height>
                    <size>{{ $image->size }}</size>
                    <views>{{ $image->views }}</views>
                    <link>http::/172.0.0.1/03_Modeule_F/i/{{ $image->image_id }}</link>
                </item>
            @endforeach
        </images>
    @else
        <images />
    @endif
</data>