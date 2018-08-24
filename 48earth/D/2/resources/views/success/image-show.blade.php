<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
    <id>{{ $image->image_id }}</id>
    <title>{{ $image->title }}</title>
    <description{!! empty($image->description) ? '/>' : '>' . $image->description . '</description>' !!}
    <datetime>{{ $image->created_at->timestamp }}</datetime>
    <width>{{ $image->width }}</width>
    <height>{{ $image->height }}</height>
    <size>{{ $image->size }}</size>
    <views>{{ $image->views }}</views>
    <link>http://192.168.0.2/01_Module_D/i/{{ $image->image_id }}.jpg</link>
</data>