<?xml version="1.0" encoding="UTF-8"?>
@if(empty($message))
    <data type="string" success="0" status="{{ $status_code }}" />
@else
    <data type="string" success="0" status="{{ $status_code }}">{{ $message }}</data>
@endif
