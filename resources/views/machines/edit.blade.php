@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

        	<a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          	<form class="" action="{{action('MachineController@update',$machine->id)}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
            	@csrf
            	<input type="hidden" name="_method" value="PUT">
            	<div class="row">
            		<div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Game Title <span style="color:red">*</span></label>
		                  <input type="text" class="form-control @error('game_title') is-invalid @enderror input100" name="game_title" value="{{$machine->game_title}}" required="">
		                  @error('game_title')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		            </div>

		            <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Owner <span style="color:red">*</span></label>
		                  <select class="form-control @error('lkp_owner_id') is-invalid @enderror input100" name="lkp_owner_id" required="">
		                    <option value=""></option>
		                      @foreach($owners as $owner)
		                        <option value="{{$owner->id}}"  {{ $machine->lkp_owner_id == $owner->id ? 'selected' : '' }}>{{$owner->value}}</option>
		                      @endforeach
		                  </select>
		                  @error('lkp_owner_id')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		            </div>

		            <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Brand</label>
		                  <select class="form-control @error('machine_brand_id') is-invalid @enderror input100" name="machine_brand_id">
		                    <option value=""></option>
		                      @foreach($brands as $brand)
		                        <option value="{{$brand->id}}" {{ $machine->machine_brand_id == $brand->id ? 'selected' : '' }}>{{$brand->brand}} {{$brand->model}} {{$brand->weight}}</option>
		                      @endforeach
		                  </select>
		                  @error('machine_brand_id')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		            </div>

		            <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Serial</label>
		                  <input type="text" class="form-control @error('serial') is-invalid @enderror input100" name="serial" value="{{$machine->serial}}">
		                  @error('serial')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		            </div>

		            <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Inventory</label>
		                  <input type="text" class="form-control @error('inventory') is-invalid @enderror input100" name="inventory" value="{{$machine->inventory}}">
		                  @error('inventory')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		              </div>

		              <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Address</label>
		                  <select class="form-control @error('address_id') is-invalid @enderror input100" name="address_id">
		                    <option value=""></option>
		                      @foreach($addresses as $address)
		                        <option value="{{$address->id}}" {{ $machine->address_id == $address->id ? 'selected' : '' }}>{{$address->name}} - {{$address->name_address}}</option>
		                      @endforeach
		                  </select>
		                  @error('address_id')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		              </div>

		              <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Status</label>
		                  <select class="form-control @error('lkp_status_id') is-invalid @enderror input100" name="lkp_status_id">
		                    <option value=""></option>
		                      @foreach($status as $st)
		                        <option value="{{$st->id}}" {{ $machine->lkp_status_id == $st->id ? 'selected' : '' }}>{{$st->value}}</option>
		                      @endforeach
		                  </select>
		                  @error('lkp_status_id')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		              </div>

		              <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Price</label>
		                  <input type="number" class="form-control @error('price') is-invalid @enderror input100" name="price" value="{{$machine->price}}">
		                  @error('price')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		              </div>

		              <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Parts</label>
		                  <select class="form-control selectpicker show-menu-arrow @error('parts') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Part --" multiple="multiple" name="parts_ids[]">
		                  @foreach($parts as $part)
		                    <option {{ in_array($part->id, $parts_ids) ? 'selected' : '' }}  value="{{$part->id}}">{{$part->serial}} - {{$part->value}}</option>
		                  @endforeach
		                  </select>
		                  @error('parts_ids')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
		              </div>

		              <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Image @if($machine->image) <a href="#" class="btn btn-link" style="width:40px; margin: 0" data-toggle="modal" data-target="#exampleModalCenter"><i class="far fa-eye"></i></a> @endif </label>
		                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
		                    @if($machine->image)
		                    <img src="{{asset('/images/machines')}}/{{$machine->image}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
		                    @else
		                    <img src="{{asset('/images/interface.png')}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
		                    @endif
		                  </div>
		                  <input class="photo" type="file" name="image" value="" id="img-btn-3" data-id2="img-new-3" accept="image/*" hidden>
		                  <input class="mg" type="text" value="" id="img-new-3" accept="image/*" hidden>
		                </div>
		              </div>
            	</div>
            	 <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <button type="submit" class="btn btn-success">Save</button>
                </div>
              </div>
            </div>
         	</form>
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
