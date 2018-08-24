<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
    <id>{{ $image->image_id }}</id>
    <datetime>{{ $image->updated_at->timestamp }}</datetime>
    <width>{{ $image->width }}</width>
    <height>{{ $image->height }}</height>
    <size>{{ $image->size }}</size>
    <view>{{ $image->view }}</view>
    <link>http://127.0.0.1/02_Modue_D/{{ $image->image_id }}.jpg</link>
</data>