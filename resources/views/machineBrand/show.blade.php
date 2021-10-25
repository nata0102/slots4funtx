@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{action('MachineBrandController@index')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></a>

          <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Brand</label>
                <input type="text" class="form-control" disabled name="brand" value="{{$brand->brand}}">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Model</label>
                <input type="text" class="form-control" disabled name="model" value="{{$brand->model}}">
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="form-group">
                <label for="">Weight</label>
                <input type="text" class="form-control" disabled name="weight" value="{{$brand->weight}}">
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  @stop
