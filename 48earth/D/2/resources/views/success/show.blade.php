<?xml version="1.0" encoding="UTF-8"?>
@if(empty($message))
    <data success="1" status="{{ $status_code }}"/>
@else
    <data type="string" success="1" status="{{ $status_code }}">{{ $message }}</data>
@endif