<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
    <id>{{ $id }}</id>
    <title>{{ $title }}</title>
    <description>{{ $description }}</description>
    <datetime>{{ $datetime }}</datetime>
    @if(isset($images))
    <covers>
        @foreach($images as $val)
        <cover>{{ $val['id'] }}</cover>
        @endforeach
    </covers>
    @else
    </covers>
    @endif
    
    <account>{{ $account }}</account>
    <link>{{ $link }}</link>
    <images_count>{{ $images_count }}</images_count>

    <images>
    @if(isset($images))
        @foreach($images as $val)
        <item>
            <id>{{ $val['id'] }}</id>
            <title>{{ $val['title'] }}</title>
            <description>{{ $val['description'] }}</description>
            <datetime>{{ $val['datetime'] }}</datetime>
            <width>{{ $val['width'] }}</width>
            <height>{{ $val['height'] }}</height>
            <size>{{ $val['size'] }}</size>
            <views>{{ $val['views'] }}</views>
            <link>{{ $val['link'] }}</link>
        </item>
        @endforeach
    </images>
    @else
    </images>
    @endif

</data>