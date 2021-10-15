@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <a href="{{action('PartController@create')}}" class="btn btn-info">Nueva Pieza</a>
        <div class="" id="card-section">

          @foreach($parts as $part)
            <p>{{$part->id}}</p>
          @endforeach

        </div>
      </div>
    </div>
  </div>

  @stop
