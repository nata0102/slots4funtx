@extends('layouts.app')

@section('content')

  <div class="" style="background-color: red;">
    @foreach($res as $r)
      <p>{{$r->id}}</p>
    @endforeach
    <p>hola</p>
  </div>

@stop
