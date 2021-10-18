@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <p><b>Brand: </b>{{$part->brand}}</p>

          <p><b>Model: </b>{{$part->model}}</p>

          <p><b>Serial: </b>{{$part->serial}}</p>

          <p><b>Price: </b>${{number_format($part->price,'2','.',',')}}</p>

          <p><b>Weight: </b>{{$part->weight}}</p>

          <p><b>Type: </b>
          @if($part->type != NULL)
            {{$part->type->value}}
          @endif
          <p>

          <p><b>Status: </b>
          @if($part->status != NULL)
            {{$part->status->value}}
          @endif
          </p>

          <p><b>Protocol: </b>
          @if($part->protocol != NULL)
            {{$part->protocol->value}}
          @endif

          <p><b>Description: </b></p>
          <p>{{$part->description}}</p>

          <p><b>Image: </b></p>
          <img src="{{asset('/images/part')}}/{{$part->image}}" alt="" style="width: 300px;">

        </div>
      </div>
    </div>
  </div>

  @stop
