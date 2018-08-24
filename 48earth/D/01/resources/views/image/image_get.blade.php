<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
    <id>{{ $id }}</id>
    <title>{{ $title }}</title>
    @if(!empty($description))
    <description>{{ $description }}</description>
    @else
    <description/>
    @endif
    <datetime>{{ $datetime }}</datetime>
    <width>{{ $width }}</width>
    <height>{{ $height }}</height>
    <size>{{ $size }}</size>
    <views>{{ $views }}</views>
    <link>{{ $link }}</link>
</data>