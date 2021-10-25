@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{action('MachineBrandController@index')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></a>

          <form class="" action="{{action('MachineBrandController@update',$brand->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id" value="brand->id}}">

            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand</label>
                  <input type="text" class="form-control @error('brand') is-invalid @enderror input100" name="brand" value="{{$brand->brand}}" required>
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
                  <input type="text" class="form-control @error('model') is-invalid @enderror input100" name="model" value="{{$brand->model}}" required>
                  @error('model')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Weight</label>
                  <input type="text" class="form-control @error('weight') is-invalid @enderror input100" name="weight" value="{{$brand->weight}}" required>
                  @error('weight')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
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


  @stop
