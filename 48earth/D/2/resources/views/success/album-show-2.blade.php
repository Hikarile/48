<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
    <id>{{ $album->album_id }}</id>
    <title>{{ $album->title }}</title>
    <account>{{ $account->account_id }}</account>
    <link>http://192.168.0.2/01_Module_D/album/{{ $album->album_id }}</link>
    <images_count>{{ $album->images()->count() }}</images_count>
    <images>
        @foreach($images as $image)
            <item>
                <id>{{ $image->image_id }}</id>
                <title>{{ $image->title }}</title>
                <description{!! empty($image->description) ? '/>' : '>' . $image->description . '</description>' !!}
                <datetime>{{ $image->created_at->timestamp }}</datetime>
                <width>{{ $image->width }}</width>
                <height>{{ $image->height }}</height>
                <size>{{ $image->size }}</size>
                <views>{{ $image->views }}</views>
                <link>http://192.168.0.2/01_Module_D/i/{{ $image->image_id }}.jpg</link>
            </item>
        @endforeach
    </images>
</data>