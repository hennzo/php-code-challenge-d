@extends('layouts.app')
@section('con1')
@if(count($services)>0)
<ui class="list-group">
    @foreach($services as $service)
        <li class="list-group-item">{{$service}}</li>
    @endforeach
</ui>
@endif
@endsection
    