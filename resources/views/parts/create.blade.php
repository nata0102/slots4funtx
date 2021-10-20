@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <button data-action="{{action('PartController@index')}}" data-message="Data will be lost!" class=" back-button btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></button>
          <form class="" action="{{action('PartController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf

            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand</label>
                  <input type="text" class="form-control @error('brand') is-invalid @enderror input100" name="brand" value="{{old('brand')}}">
                  @error('brand')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Model</label>
                  <input type="text" class="form-control @error('model') is-invalid @enderror input100" name="model" value="{{old('model')}}">
                  @error('model')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Serial</label>
                  <input type="text" class="form-control @error('serial') is-invalid @enderror input100" name="serial" value="{{old('serial')}}">
                  @error('serial')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Price</label>
                  <input type="text" class="form-control @error('price') is-invalid @enderror input100" name="price" value="{{old('price')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                  @error('price')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Weight</label>
                  <input type="text" class="form-control @error('weight') is-invalid @enderror input100" name="weight" value="{{old('weight')}}">
                  @error('weight')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Status</label>
                  <select class="form-control @error('status') is-invalid @enderror input100" name="status">
                    <option value=""></option>
                    @foreach($status as $status)
                      <option value="{{$status->id}}" {{ old('status') == $status->id ? 'selected' : '' }}>{{$status->value}}</option>
                    @endforeach
                  </select>
                  @error('status')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select class="form-control @error('type') is-invalid @enderror input100" name="type">
                    <option value=""></option>
                    @foreach($types as $type)
                      <option value="{{$type->id}}" {{ old('type') == $type->id ? 'selected' : '' }} >{{$type->value}}</option>
                    @endforeach
                  </select>
                  @error('type')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Protocol</label>
                  <select class="form-control @error('protocol') is-invalid @enderror input100" name="protocol">
                    <option value=""></option>
                    @foreach($protocols as $protocol)
                      <option value="{{$protocol->id}}" {{ old('protocol') == $protocol->id ? 'selected' : '' }} >{{$protocol->value}}</option>
                    @endforeach
                  </select>
                  @error('protocol')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Machine</label>
                  <select class="form-control selectpicker show-menu-arrow @error('parts') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Machine --" name="machine_id">
                  @foreach($machines as $machine)
                    <option  {{ (collect(old('machine_id'))->contains($machine->id)) ? 'selected':'' }}  value="{{$machine->id}}">{{$machine->game_title}} - {{$machine->serial}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Image</label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    <img src="{{asset('/images/interface.png')}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                  </div>
                  <input class="photo" type="file" name="image" value="{{old('image')}}" id="img-btn-3" data-id2="img-new-3" accept="image/*" hidden>
                  <input class="mg" type="text" value="" id="img-new-3" accept="image/*" hidden>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label for="">Description</label>
                  <textarea name="description" class="form-control @error('description') is-invalid @enderror input100" rows="8" cols="80" style="width: 100%; height:  5rem;">{{old('description')}}</textarea>
                  @error('description')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12">
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

  @stop
