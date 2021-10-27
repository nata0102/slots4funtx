@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></a>

        	<div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Game Title</label>
                  <input type="text" class="form-control" disabled value="{{$machine->game_title}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Owner</label>
                  <input type="text" class="form-control" disabled value="{{$machine->owner->value}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand</label>
                  @if($machine->brand != null)
                  	<input type="text" class="form-control" disabled value="{{$machine->brand->brand}} {{$machine->brand->model}} {{$machine->brand->weight}}">
                  @else
                  	<input type="text" class="form-control" disabled value="">
                  @endif
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Serial</label>
                  <input type="text" class="form-control" disabled value="{{$machine->serial}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Inventory</label>
                  <input type="text" class="form-control" disabled value="{{$machine->inventory}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Address</label>
                  @if($machine->address != null)
                  	<input type="text" class="form-control" disabled value="{{$machine->address->client->name}} - {{$machine->address->name_address}}">
                  @else
                  	<input type="text" class="form-control" disabled value="">
                  @endif
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Status</label>
                  @if($machine->status != null)
                  	<input type="text" class="form-control" disabled value="{{$machine->status->value}}">
                  @else
                  	<input type="text" class="form-control" disabled value="">
                  @endif
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Price</label>
                  <input type="number" class="form-control" disabled value="{{$machine->price}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Image @if($machine->image) <a href="#" class="btn btn-link" style="width:40px; margin: 0" data-toggle="modal" data-target="#exampleModalCenter"><i class="far fa-eye"></i></a> @endif </label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    @if($machine->image)
                    <img src="{{asset('/images/machines')}}/{{$machine->image}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                    @endif
                  </div>
                </div>
              </div>

              @if(count($machine->parts) > 0)
              <div class="form-group">
              	<label for="">Parts</label>
	              <div class=" table-responsive table-striped table-bordered" >
	                <table id="example" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
	                    <thead>
	                        <tr>
	                        	<th>Type</th>
	                        	<th>Brand</th>
	                        	<th>Model</th>
	                        	<th>Serial</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($machine->parts as $part)
	                        <tr>
	                            <td>{{$part->type->value}}</td>
	                            <td>{{$part->brand}}</td>
	                            <td>{{$part->model}}</td>
	                            <td>{{$part->serial}}</td>
	                        </tr>
	                        @endforeach
	                    </tbody>
	                </table>
	                </div>
	            </div>
              @endif

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
        <h5 class="modal-title" id="exampleModalCenterTitle">Actual Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="padding: 0;">
        <img src="{{asset('/images/machines')}}/{{$machine->image}}" alt="" style="width: 100%;">
      </div>

    </div>
  </div>
</div>

@stop
