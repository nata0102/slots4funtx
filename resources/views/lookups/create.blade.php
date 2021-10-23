@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          
          <a href="{{action('LookupController@index')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          
          <form class="" action="{{action('LookupController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Game Title <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('game_title') is-invalid @enderror input100" name="game_title" value="{{old('game_title')}}" required="">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Owner <span style="color:red">*</span></label>
                  <select class="form-control @error('lkp_owner_id') is-invalid @enderror input100" name="lkp_owner_id" required="">
                    <option value=""></option>
                      @foreach($owners as $owner)
                        <option value="{{$owner->id}}"  {{ old('lkp_owner_id') == $owner->id ? 'selected' : '' }}>{{$owner->value}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand</label>
                  <select class="form-control @error('machine_brand_id') is-invalid @enderror input100" name="machine_brand_id">
                    <option value=""></option>
                      @foreach($brands as $brand)
                        <option value="{{$brand->id}}" {{ old('machine_brand_id') == $brand->id ? 'selected' : '' }}>{{$brand->brand}} {{$brand->model}} {{$brand->weight}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Serial</label>
                  <input type="text" class="form-control @error('serial') is-invalid @enderror input100" name="serial" value="{{old('serial')}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Inventory</label>
                  <input type="text" class="form-control @error('inventory') is-invalid @enderror input100" name="inventory" value="{{old('inventory')}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Address</label>
                  <select class="form-control @error('address_id') is-invalid @enderror input100" name="address_id">
                    <option value=""></option>
                      @foreach($addresses as $address)
                        <option value="{{$address->id}}" {{ old('address_id') == $address->id ? 'selected' : '' }}>{{$address->name}} - {{$address->name_address}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Status</label>
                  <select class="form-control @error('lkp_status_id') is-invalid @enderror input100" name="lkp_status_id">
                    <option value=""></option>
                      @foreach($status as $st)
                        <option value="{{$st->id}}" {{ old('lkp_status_id') == $st->id ? 'selected' : '' }}>{{$st->value}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Price</label>
                  <input type="number" class="form-control @error('price') is-invalid @enderror input100" name="price" value="{{old('price')}}">
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Parts</label>
                  <select class="form-control selectpicker show-menu-arrow @error('parts') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Part --" multiple="multiple" name="parts_ids[]">
                  @foreach($parts as $part)
                    <option  {{ (collect(old('parts_ids'))->contains($part->id)) ? 'selected':'' }}  value="{{$part->id}}">{{$part->serial}} - {{$part->value}}</option>
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
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @stop
