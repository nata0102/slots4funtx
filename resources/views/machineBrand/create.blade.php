@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{action('MachineBrandController@index')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></a>
          <form class="" action="{{action('MachineBrandController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf

            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('brand') is-invalid @enderror input100" name="brand" value="{{old('brand')}}" required>
                  @error('brand')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Model <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('model') is-invalid @enderror input100" name="model" value="{{old('model')}}" required>
                  @error('model')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Weight <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('weight') is-invalid @enderror input100" name="weight" value="{{old('weight')}}" required>
                  @error('weight')
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
