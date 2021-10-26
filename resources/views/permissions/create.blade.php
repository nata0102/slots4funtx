@extends('layouts.layout')

@section('content')

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="card" id="card-section">
          
        <a href="{{action('PermissionController@index')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          
        <form class="" action="{{action('PermissionController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">
                     

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type Permit <span style="color:red">*</span></label>
                  <select class="form-control @error('lkp_type_permit_id') is-invalid @enderror input100" name="lkp_type_permit_id" required="">
                    <option value=""></option>
                      @foreach($types as $type)
                        <option value="{{$type->id}}"  {{ old('lkp_type_permit_id') == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_type_permit_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Machine <span style="color:red">*</span></label>
                  <select class="form-control @error('machine_id') is-invalid @enderror input100" name="machine_id" required="">
                    <option value=""></option>
                      @foreach($machines as $machine)
                        <option value="{{$machine->id}}"  {{ old('machine_id') == $machine->id ? 'selected' : '' }}>{{$machine->game_title}}</option>
                      @endforeach
                  </select>
                  @error('machine_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Permit Number <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('permit_number') is-invalid @enderror input100" name="permit_number" value="{{old('permit_number')}}" required="">
                  @error('permit_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Date Permit <span style="color:red">*</span></label>
                  <input type="date" class="form-control @error('date_permit') is-invalid @enderror input100" name="date_permit" value="date_permit" id="datepicker" required="">
                  @error('date_permit')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
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
