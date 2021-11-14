@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>


            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Name') }} <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror input100" required name="name" value="{{$client->name}}" disabled>
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Email') }} <span style="color:red">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror input100" required name="email" value="{{$client->email}}" disabled>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Phone') }} <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('phone') is-invalid @enderror input100" required name="phone" value="{{$client->phone}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" disabled>
                  @error('phone')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Date of Birthday') }}</label>
                  <input type="text" class="form-control @error('dob') is-invalid @enderror input100" name="dob" value="{{$client->dob}}" disabled>
                  @error('dob')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Referral') }}</label>
                  <input type="text" class="form-control @error('referral') is-invalid @enderror input100" name="referral" value="{{$client->referral}}" disabled>
                  @error('referral')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>


              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">{{ __('Photo') }} @if($client->photo) <a href="#" class="btn btn-link" style="width:40px; margin: 0" data-toggle="modal" data-target="#exampleModalCenter"><i class="far fa-eye"></i></a> @endif </label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; overflow: hidden; position: relative;" class="" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    @if($client->photo && file_exists(public_path().'/images/clients/'.$client->photo))
                      <img src="{{asset('/images/clients')}}/{{$client->photo}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                    @endif
                  </div>
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
          <h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Actual image') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" style="padding: 0;">
          <img src="{{asset('/images/clients')}}/{{$client->photo}}" alt="" style="width: 100%;">
        </div>

      </div>
    </div>
  </div>

  @stop
