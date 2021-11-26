@extends('layouts.layout')

@section('content')

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="card" id="card-section">

        <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

        <form class="" action="{{action('PermissionController@storeByRank')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
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
                  <label for="">Starting Range<span style="color:red">*</span></label>
                  <input type="text" maxlength="6" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control @error('start_range') is-invalid @enderror input100" name="start_range" value="{{old('start_range')}}" required="">
                  @error('start_range')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Final Rank<span style="color:red">*</span></label>
                  <input type="text" maxlength="6" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control @error('final_range') is-invalid @enderror input100" name="final_range" value="{{old('final_range')}}" required="">
                  @error('final_range')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" >
                <div class="form-group">
                  <label for="">Permit Year <span style="color:red">*</span></label>
                  <input id="year_permit" type="number" name="year_permit" min="2021" max="2035"
                  class="form-control @error('year_permit') is-invalid @enderror input100" value="{{old('year_permit')}}" required>
                  @error('year_permit')
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

<script>
  window.onload = function() {
    const d = new Date();
    document.getElementById("year_permit").value = d.getFullYear();
  };
</script>
@stop
