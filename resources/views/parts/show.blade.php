@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{action('PartController@index')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></a>

          <div class="row">

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Brand</label>
                <input type="text" class="form-control" disabled name="brand" value="{{$part->brand}}">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Model</label>
                <input type="text" class="form-control" disabled name="model" value="{{$part->model}}">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Serial</label>
                <input type="text" class="form-control" disabled name="serial" value="{{$part->serial}}">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Price</label>
                <input type="text" class="form-control" disabled name="price" value="{{$part->price}}">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Weight</label>
                <input type="text" class="form-control" disabled name="weight" value="{{$part->weight}}">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Status</label>
                <select class="form-control" name="status" disabled>
                  <option value=""></option>
                  @foreach($status as $status)
                    <option value="{{$status->id}}" {{$part->lkp_status_id == $status->id ? 'selected' : ''}}>{{$status->value}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Type</label>
                <select class="form-control" name="type" disabled>
                  <option value=""></option>
                  @foreach($types as $type)
                    <option value="{{$type->id}}" {{$part->lkp_type_id == $type->id ? 'selected' : ''}}>{{$type->value}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Protocol</label>
                <select class="form-control" name="protocol" disabled>
                  <option value=""></option>
                  @foreach($protocols as $protocol)
                    <option value="{{$protocol->id}}" {{$part->lkp_protocol_id == $protocol->id ? 'selected' : ''}}>{{$protocol->value}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Machine</label>
                @if($part->machine_id != NULL)
                <input type="text" class="form-control" disabled name="machine_id" value="{{$part->machine->game_title}} - {{$part->machine->serial}}">
                @else
                <input type="text" class="form-control" disabled name="machine_id" value="">

                @endif
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Image @if($part->image) <a href="#" class="btn btn-link" style="width:40px; margin: 0" data-toggle="modal" data-target="#exampleModalCenter"><i class="far fa-eye"></i></a> @endif</label>
                <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;">
                  @if($part->image && file_exists(public_path().'/images/part/'.$part->image))
                  <img src="{{asset('/images/part')}}/{{$part->image}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                  @else
                  <img src="{{asset('/images/interface.png')}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                  @endif
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="form-group">
                <label for="">Description</label>
                <textarea name="description" class="form-control" rows="8" cols="80" style="width: 100%; height:  5rem;" disabled>{{$part->description}}</textarea>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Actual image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding: 0;">
        <img src="{{asset('/images/part')}}/{{$part->image}}" alt="" style="width: 100%;">
      </div>

    </div>
  </div>
</div>

  @stop
