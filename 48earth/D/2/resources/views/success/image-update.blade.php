<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
    <id>{{ $image->image_id }}</id>
    <datetime>{{ $image->updated_at->timestamp }}</datetime>
    <width>{{ $image->width }}</width>
    <height>{{ $image->height }}</height>
    <size>{{ $image->size }}</size>
    <link>http://192.168.0.2/01_Module_D/i/{{ $image->image_id }}.jpg</link>
</data>