@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <form class="" action="{{action('PartController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
              <label for="">Marca</label>
              <input type="text" class="form-control" name="brand" value="">
            </div>

            <div class="form-group">
              <label for="">Modelo</label>
              <input type="text" class="form-control" name="model" value="">
            </div>

            <div class="form-group">
              <label for="">Serial</label>
              <input type="text" class="form-control" name="serial" value="">
            </div>

            <div class="form-group">
              <label for="">Precio</label>
              <input type="text" class="form-control" name="price" value="">
            </div>

            <div class="form-group">
              <label for="">Peso</label>
              <input type="text" class="form-control" name="weight" value="">
            </div>



            <div class="form-group">
              <label for="">Status</label>
              <select class="form-control" name="status">
                <option value="">-</option>
                @foreach($status as $status)
                  <option value="{{$status->id}}">{{$status->value}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Tipo</label>
              <select class="form-control" name="type">
                <option value="">-</option>
                @foreach($types as $type)
                  <option value="{{$type->id}}">{{$type->value}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Protocolo</label>
              <select class="form-control" name="protocol">
                <option value="">-</option>
                @foreach($protocols as $protocol)
                  <option value="{{$protocol->id}}">{{$protocol->value}}</option>
                @endforeach
              </select>
            </div>



            <div class="form-group">
              <label for="">Activo</label>
              <input type="hidden" class="" name="active" value="0">
              <input type="checkbox" class="" name="active" value="1">
            </div>

            <div class="form-group">
              <label for="">Imagen</label>
              <input type="file" name="image" value="" accept="image/*">
            </div>

            <div class="form-group">
              <label for="">Descripción</label>
              <textarea name="description" class="form-control" rows="8" cols="80" style="width: 100%; height:  5rem; rezize: none;"></textarea>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success">Guardar</button>
            </div>


          </form>

        </div>
      </div>
    </div>
  </div>

  @stop
