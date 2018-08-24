<id>{{ $image->image_id }}</id>
<title>{{ $image->title }}</title>
@if($image->description != '')
<description>{{ $image->description }}</description>
@else
</description>
@endif
<width>{{ $image->width }}</width>
<height>{{ $image->height }}</height>
<size>{{ $image->size }}</size>
<views>{{ $image->views }}</views>
<link>http:\\127.0.0.1/08_Module_D/idate{{ $image->image_id }}.jpg</link>