<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
    <id>{{ $image->image_id }}</id>
    <title>{{ $image->title }}</title>
    <description>{{ $image->description }}</description>
    <width>{{ $image->width }}</width>
    <height>{{ $image->height }}</height>
    <size>{{ $image->size }}</size>
    <views>{{ $image->views }}</views>
    <link>http://127.0.0.1/05_Noudle_D/i/{{ $image->image_id }}.jpg</link>
</data>