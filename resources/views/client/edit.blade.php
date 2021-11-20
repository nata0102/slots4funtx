@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          <form class="" action="{{action('ClientController@update',$client->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror input100" required name="name" value="{{$client->name}}">
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
                  <input type="email" class="form-control @error('email') is-invalid @enderror input100" required name="email" value="{{$client->email}}">
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
                  <input type="text" class="form-control @error('phone') is-invalid @enderror input100" required name="phone" value="{{$client->phone}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
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
                  <input type="date" class="form-control @error('dob') is-invalid @enderror input100" name="dob" value="{{$client->dob}}">
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
                  <input type="text" class="form-control @error('referral') is-invalid @enderror input100" name="referral" value="{{$client->referral}}">
                  @error('referral')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>


              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Photo @if($client->photo) <a href="#" class="btn btn-link" style="width:40px; margin: 0" data-toggle="modal" data-target="#exampleModalCenter"><i class="far fa-eye"></i></a> @endif </label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    @if($client->photo && file_exists(public_path().'/images/clients/'.$client->photo))
                    <img src="{{asset('/images/clients')}}/{{$client->photo}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                    @else
                    <img src="{{asset('/images/interface.png')}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                    @endif
                  </div>
                  <input class="photo" type="file" name="image" value="" id="img-btn-3" data-id2="img-new-3" accept="image/*" hidden>
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


          <!--Direcciones-->

          <div class="card">
            <h4>{{ __('Addresses') }}</h4>
            <button class="btn btn-info" style="width: 40px; margin-bottom: 10px; position: absolute; right: 10px; top: 8px;" data-toggle="modal" data-target="#modalCreate"><i class="fas fa-plus"></i></button>

            <div id="table">

              @if($addresses->count() > 0)

                <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0; margin-top: 18px;">
                  <table class="table" style="width: 100%; table-layout: fixed;">
                    <thead>
                      <tr>
                        <th style="width:200px; text-align: center;">{{ __('Name Address') }}</th>
                        <th style="width:175px; text-align: center;">{{ __('Business Name') }}</th>
                        <th style="width:200px; text-align: center;">{{ __('City') }}</th>
                        <th style="width:80px; text-align: center;">{{ __('County') }}</th>
                        <th style="width:125px; text-align: center;"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($client->addresses as $address)
                        @if($address->active == 1)
                          <tr>
                            <td>{{$address->name_address}}</td>
                            <td>{{$address->business_name}}</td>
                            @if($address->city)                            
                              <td>{{ $address->city->value }}</td>
                            @else
                              <td>-</td>
                            @endif
                            @if($address->county)
                              <td>{{ $address->county->value }}</td>
                            @else
                              <td>-</td>
                            @endif
                            <td>
                              <div class="row" style="margin-right: 0; margin-left: 0;">

                                <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                  <button data-name_address="{{$address->name_address}}" data-business_name="{{$address->business_name}}" data-city="{{$address->lkp_city_id}}" data-country="{{$address->lkp_county_id}}" data-action="{{action('AddressController@update',$address->id)}}" class="btn btn-link editAddress" style="width:40px; margin: 0; padding-top: 0;" data-toggle="modal" data-target="#modalEdit"><i class="far fa-edit"></i></button>
                                </div>

                                <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                  <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="{{__('You won\'t be able to revert this!')}}" data-message2="{{ __('Deleted!')}}" data-message3="{{ __('The address has been deleted.')}}" data-method="DELETE" data-action="{{action('AddressController@destroy',$address->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                                </div>

                              </div>
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>


    <!-- Modal -->
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="createTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

          <form class="Form" action="{{action('AddressController@store')}}" id="createAddress" method="post" data-reload="1" data-refresh="#table" data-message1="{{ __('Successful!!')}}" data-message2="{{ __('The address has been created.')}}" data-modal="#modalCreate">
          @csrf
          <input type="hidden" name="client_id" value="{{$client->id}}">

            <div class="modal-header">
              <h5 class="modal-title" id="createTitle">{{ __('New Address') }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="form-group">
                <label for="">{{ __('Address') }} <span style="color:red">*</span></label>
                <input type="text" class="form-control @error('name_address') is-invalid @enderror input100" name="name_address" value="" required>
                @error('name_address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="">{{ __('Business Name') }}</label>
                <input type="text" class="form-control @error('business_name') is-invalid @enderror input100" name="business_name" value="">
                @error('business_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="">{{ __('City') }}</label>
                <select class="form-control @error('lkp_city_id') is-invalid @enderror input100" name="lkp_city_id" id="client-city">
                  <option value="" selected disabled></option>
                    @foreach($cities as $tp)
                      <option value="{{$tp->id}}">{{$tp->value}}</option>
                    @endforeach
                </select>
              </div>

              <div class="form-group" hidden id="client-county-1">
                <label for="">{{ __('County') }}</label>
                <select class="form-control @error('lkp_county_id') is-invalid @enderror input100" name="lkp_county_id">
                  <option value="" selected disabled></option>
                    @foreach($counties as $tp)
                      <option value="{{$tp->id}}" class="city-{{$tp->lkp_city_id}} counties" hidden>{{$tp->value}}</option>
                    @endforeach
                </select>
              </div>
            </div>

            <div class="modal-footer">
              <button type="submit" name="button" class="btn btn-success formButton" data-form="createAddress">{{__('Save')}}</button>
            </div>

          </form>

        </div>
      </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="editTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

          <form class="Form" method="post" id="updateAddress" data-reload="1" data-refresh="#table" data-message1="{{ __('Successful!!')}}" data-message2="{{ __('The address has been updated.')}}" data-modal="#modalEdit">
          @csrf
          <input type="hidden" name="_method" value="PUT">

            <div class="modal-header">
              <h5 class="modal-title" id="editTitle">{{ __('Update Address') }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="form-group" >
                <label for="">{{ __('Address') }} <span style="color:red">*</span></label>
                <input type="text" class="form-control @error('name_address') is-invalid @enderror input100" name="name_address" value="" id="name_address" required>
                @error('name_address')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="">{{ __('Business Name') }}</label>
                <input type="text" class="form-control @error('business_name') is-invalid @enderror input100" name="business_name" value="" id="business_name">
                @error('business_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="">{{ __('City') }}</label>
                <select class="form-control @error('lkp_city_id') is-invalid @enderror input100" name="lkp_city_id" id="client-city2">
                  <option value="" selected disabled></option>
                    @foreach($cities as $tp)
                      <option value="{{$tp->id}}" id="city-{{$tp->id}}">{{$tp->value}}</option>
                    @endforeach
                </select>
              </div>

              <div class="form-group" id="client-county-2">
                <label for="">County</label>
                <select class="form-control @error('lkp_county_id') is-invalid @enderror input100" name="lkp_county_id" id="client-county2">
                  <option value="" selected disabled></option>
                    @foreach($counties as $tp)
                      <option value="{{$tp->id}}" class="city-{{$tp->lkp_city_id}} counties" id="county-{{$tp->id}}">{{$tp->value}}</option>
                    @endforeach
                </select>
              </div>

            </div>

            <div class="modal-footer">
              <button type="submit" name="button" class="btn btn-success formButton" data-form="updateAddress">{{__('Save')}}</button>
            </div>

          </form>
        </div>
      </div>
    </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalCenterTitle">Actual image</h5>
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
