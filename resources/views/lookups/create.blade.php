@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          <form class="" action="{{action('LookupController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select id="type-lookup" class="form-control @error('type') is-invalid @enderror input100" name="type" required="">
                    <option value=""></option>
                      @foreach($types as $tp)
                        <option value="{{$tp->key_value}}"  {{ old('type') == $tp->key_value ? 'selected' : '' }}>{{$tp->value}}</option>
                      @endforeach
                  </select>
                   @error('type')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" hidden id="city-form">
                <div class="form-group">
                  <label for="">City <span style="color:red">*</span></label>
                  <select class="form-control @error('type') is-invalid @enderror input100" name="lkp_city_id" required="" id="city-select">
                    <option value="" selected disabled></option>
                      @foreach($cities as $tp)
                        <option value="{{$tp->id}}"  {{ old('lkp_city_id') == $tp->id ? 'selected' : '' }}>{{$tp->value}}</option>
                      @endforeach
                  </select>
                   @error('lkp_city_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Value <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('value') is-invalid @enderror input100" name="value" value="{{old('value')}}" required="">
                  @error('value')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="part_type_brand" hidden>
                <div class="form-group">
                  <label for="">Brands</label>
                  <select class="form-control selectpicker show-menu-arrow @error('brands') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Brands --" multiple="multiple" name="brands_ids[]">
                  @foreach($brands as $brand)
                    <option  {{ (collect(old('brands_ids'))->contains($brand->id)) ? 'selected':'' }}  value="{{$brand->id}}">{{$brand->brand}} {{$brand->model}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="component_type" hidden>
                <div class="form-group">
                  <label for="">Components</label>
                  <select class="form-control selectpicker show-menu-arrow @error('lkp_city_id') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Component --" name="lkp_city_id">
                    @foreach($parts as $part)
                      <option value="{{$part->id}}" {{ old('lkp_city_id') == $part->id ? 'selected' : '' }} >{{$part->value}}</option>
                    @endforeach
                  </select>
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
