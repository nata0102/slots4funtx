@extends('layouts.layout')

@section('content')

  <div class="" style="background-color: red;">
    @foreach($parts as $part)
      <p>{{$part->id}}</p>
    @endforeach

    <p>hola</p>
  </div>

@stop
