@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <a href="{{action('PartController@create')}}" class="btn btn-info">Nueva Pieza</a>
        <div class="card" id="card-section">

          <form method="GET" action="{{ route('machines.index') }}">
              @csrf
              <div class="input-group mb-5">
                  <input class="form-control" type="text" name="game" autofocus placeholder="Game Title">

                  <input class="form-control" name="owner" autofocus placeholder="Owner Type (Mine/Service)">

                  <input class="form-control" name="status" autofocus placeholder="Status">

                  <input class="form-control" name="brand" autofocus placeholder="Brand">

                  <button type="submit" class="btn btn-default" name="option" value="all">Search
                      <span class="glyphicon glyphicon-search"></span>
                  </button>
              </div>
          </form>

          <table id="example" class="table table-striped table-bordered" style="width:100%;margin-top: 10px">
              <thead>
                  <tr>
                    <th>#</th>
                  	<th>Game Title</th>
                  </tr>
              </thead>
              <tbody>
                <?php $s = 0; ?>
              	@foreach($parts as $part)
                  <?php $s++; ?>
                  <tr>
                      <td>{{$n}}</td>
                      <td><a href="{{action('PartController@edit',$part->id)}}" class="btn btn-danger">Editar {{$part->id}}</a></td>
                  </tr>
                  @endforeach
              </tbody>
          </table>



        </div>
      </div>
    </div>
  </div>

  @stop
