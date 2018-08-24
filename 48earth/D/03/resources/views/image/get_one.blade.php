<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    <id>{{ $image->image_id }}</id>
    <title>{{ $image->title }}</title>
    @if($image->description != '')
    <description>{{ $image->description }}</description>
    @else
    <description />
    @endif
    <datetime>{{ $image->created_at->timestamp }}</datetime>
    <width>{{ $image->width }}</width>
    <height>{{ $image->height }}</height>
    <size>{{ $image->size }}</size>
    <views>{{ $image->views }}</views>
    <link>http://172.0.0.1/03_Model_F/i/{{ $image->image_id }}.jpg</link>
</data>