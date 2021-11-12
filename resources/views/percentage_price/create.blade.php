@extends('layouts.layout')

@section('content')

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="card" id="card-section">

        <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

        <form class="" action="{{action('PercentagePriceController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select id="percentage_type" class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" required="">
                    <option value=""></option>
                      @foreach($types as $type)
                        <option value="{{$type->id}}"  {{ old('lkp_type_id') == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_type_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Machine <span style="color:red">*</span></label>
                  <select class="form-control selectpicker @error('machine_id') is-invalid @enderror input100" name="machine_id" required="" data-live-search="true">
                      <option value="" selected>-- Select Machine --</option>
                      @foreach($machines as $machine)
                        <option value="{{$machine->id}}"  {{ old('machine_id') == $machine->id ? 'selected' : '' }}>{{$machine->id}} - {{$machine->serial}}</option>
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
                  <label for="">Payment Periodicity <span style="color:red">*</span></label>
                  <select id="percentage_periodicity" class="form-control @error('lkp_periodicity_id') is-invalid @enderror input100" name="lkp_periodicity_id" required="">
                    <option value=""></option>
                      @foreach($payments as $type)
                        <option value="{{$type->id}}"  {{ old('lkp_periodicity_id') == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_periodicity_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="content_percentage_payday" hidden>
                <div class="form-group">
                  <label for="">Payday <span style="color:red">*</span></label>
                  <input type="number" id="percentage_payday" name="payday" min="1" max="31"
                  class="form-control @error('payday') is-invalid @enderror input100" value="{{old('payday')}}" required>
                  @error('payday')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>              

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="" id="input">Percentage/Amount <span style="color:red">*</span></label>
                  <input type="number" class="form-control @error('amount') is-invalid @enderror input100" name="amount" value="{{old('amount')}}" required="">
                  @error('amount')
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
