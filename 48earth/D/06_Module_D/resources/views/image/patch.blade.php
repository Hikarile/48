<?xml version="1.0" encoding="UTF-8"?>
<data type="string" success="1" status="200">
	<id>{{ $image->image_id }}</id>
	<datetime>{{ $image->updated_at->timestamp }}</datetime>
	<width>{{ $image->width }}</width>
	<height>{{ $image->height }}</height>
	<size>{{ $image->size }}</size>
    <link>http://127.0.0.1/05_Noudle_D/i/{{ $image->image_id }}.jpg</link>
</data>