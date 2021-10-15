@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <form class="" action="{{action('PartController@update',$part->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group">
              <label for="">Marca</label>
              <input type="text" class="form-control" name="brand" value="{{$part->brand}}">
            </div>

            <div class="form-group">
              <label for="">Modelo</label>
              <input type="text" class="form-control" name="model" value="{{$part->model}}">
            </div>

            <div class="form-group">
              <label for="">Serial</label>
              <input type="text" class="form-control" name="serial" value="{{$part->serial}}">
            </div>

            <div class="form-group">
              <label for="">Precio</label>
              <input type="text" class="form-control" name="price" value="{{$part->price}}">
            </div>

            <div class="form-group">
              <label for="">Peso</label>
              <input type="text" class="form-control" name="weight" value="{{$part->weight}}">
            </div>



            <div class="form-group">
              <label for="">Status</label>
              <select class="form-control" name="status">
                <option value="">-</option>
                @foreach($status as $status)
                  <option value="{{$status->id}}" {{$part->lkp_status_id == $status->id ? 'selected' : ''}}>{{$status->value}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Tipo</label>
              <select class="form-control" name="type">
                <option value="">-</option>
                @foreach($types as $type)
                  <option value="{{$type->id}}" {{$part->lkp_type_id == $type->id ? 'selected' : ''}}>{{$type->value}}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Protocolo</label>
              <select class="form-control" name="protocol">
                <option value="">-</option>
                @foreach($protocols as $protocol)
                  <option value="{{$protocol->id}}" {{$part->lkp_protocol_id == $protocol->id ? 'selected' : ''}}>{{$protocol->value}}</option>
                @endforeach
              </select>
            </div>



            <div class="form-group">
              <label for="">Activo</label>
              <input type="hidden" class="" name="active" value="0">
              <input type="checkbox" class="" name="active" value="1" {{$part->active == 1 ? 'checked' : ''}}>
            </div>

            <div class="form-group">
              <label for="">Imagen</label>
              <img src="{{asset('/images/part')}}/{{$part->image}}" alt="" style="max-height: 6rem;">
              <input type="file" name="image" value="" accept="image/*">
            </div>

            <div class="form-group">
              <label for="">Descripción</label>
              <textarea name="description" class="form-control" rows="8" cols="80" style="width: 100%; height:  5rem; rezize: none;">{{$part->description}}</textarea>
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
