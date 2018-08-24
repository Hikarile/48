<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
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
</data>