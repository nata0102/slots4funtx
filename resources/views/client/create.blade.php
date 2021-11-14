@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          <form class="" action="{{action('ClientController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf

            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror input100" required name="name" value="{{old('name')}}">
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Email <span style="color:red">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror input100" required name="email" value="{{old('email')}}">
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Phone <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('phone') is-invalid @enderror input100" required name="phone" value="{{old('phone')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                  @error('phone')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Date of Birthday</label>
                  <input type="date" class="form-control @error('dob') is-invalid @enderror input100" name="dob" value="{{old('dob')}}">
                  @error('dob')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Referral</label>
                  <input type="text" class="form-control @error('referral') is-invalid @enderror input100" name="referral" value="{{old('referral')}}">
                  @error('referral')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>


              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Photo</label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    <img src="{{asset('/images/interface.png')}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                  </div>
                  <input class="photo" type="file" name="image" value="{{old('image')}}" id="img-btn-3" data-id2="img-new-3" accept="image/*" hidden>
                  <input class="mg" type="text" value="" id="img-new-3" accept="image/*" hidden>
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
