<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
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
    <link>http:\\127.0.0.1/07_Module_D/i/{{ $image->image_id }}.jpg</link>
</data>