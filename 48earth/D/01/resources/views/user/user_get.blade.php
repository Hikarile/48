<?xml version="1.0" encoding="UTF-8"?>
<data success="1" status="200">
	<account>{{ $account }}</account>
	<bio>{{ $bio }}</bio>
	<created>{{ $created }}</created>
	@if(empty($albums))
	</albums>
	@else
		<albums>
		@foreach($albums as $val)
			<album id="{{ $val['id'] }}" count="{{ $val['count'] }}"/>
		@endforeach
		</albums>
	@endif
</data>