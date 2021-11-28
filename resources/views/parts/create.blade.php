@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          <form class="" action="{{action('PartController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf

            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select id="parts_type" onchange="fillBrand(this.value, {{$brands}})" class="form-control selectpicker @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" data-live-search="true" title="-- Select Type --">
                    <option value=""></option>
                    @foreach($types as $type)
                      <option value="{{$type->id}}" {{ old('lkp_type_id') == $type->id ? 'selected' : '' }} >{{$type->value}}</option>
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
                  <label for="">Brand-Model</label>
                  <select class="form-control selectpicker @error('brand_id') is-invalid @enderror input100" name="brand_id" id="parts_brands" data-live-search="true" title="-- Select Brand --">
                  </select>
                  @error('brand_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Protocol</label>
                  <select class="form-control @error('lkp_protocol_id') is-invalid @enderror input100" name="lkp_protocol_id">
                    <option value=""></option>
                    @foreach($protocols as $protocol)
                      <option value="{{$protocol->id}}" {{ old('lkp_protocol_id') == $protocol->id ? 'selected' : '' }} >{{$protocol->value}}</option>
                    @endforeach
                  </select>
                  @error('lkp_protocol_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Status</label>
                  <select class="form-control @error('lkp_status_id') is-invalid @enderror input100" name="lkp_status_id">
                    <option value=""></option>
                    @foreach($status as $status)
                      <option value="{{$status->id}}" {{ old('lkp_status_id') == $status->id ? 'selected' : '' }}>{{$status->value}}</option>
                    @endforeach
                  </select>
                  @error('lkp_status_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Serial <span style="color:red">*</span></label>
                  <input type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return valideKey(event);"  class="form-control @error('serial') is-invalid @enderror input100" name="serial" value="{{old('serial')}}" required>
                  @error('serial')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Price</label>
                  <input type="text" class="form-control @error('price') is-invalid @enderror input100" name="price" value="{{old('price')}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                  @error('price')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Machine</label>
                  <select class="form-control selectpicker show-menu-arrow @error('machine_id') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Machine --" name="machine_id">
                  @foreach($machines as $machine)
                    <option  {{ (collect(old('machine_id'))->contains($machine->id)) ? 'selected':'' }}  value="{{$machine->id}}">{{$machine->id}} - {{$machine->serial}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label for="">Description</label>
                  <textarea name="description" class="form-control @error('description') is-invalid @enderror input100" rows="8" cols="80" style="width: 100%; height:  5rem;">{{old('description')}}</textarea>
                  @error('description')
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

<script>
  function fillBrand(type, brands){
    $('#parts_brands').empty();
    $('#parts_brands').append('<option value=""></option>');
    for(var i=0; i < brands.length; i++){
      if(type == brands[i].lkp_part_id){
        $('#parts_brands').append('<option value="'+brands[i].id+'">'+brands[i].brand+' '+brands[i].model+'</option>');
      }
    }
    selectionBrand(type,brands);
  }

  function selectionBrand(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#parts_brands option[value='" + e + "']").prop("selected", true);
      });
      $("#parts_brands").selectpicker("refresh");
  }

  function valideKey(evt){
      // code is the decimal ASCII representation of the pressed key.
      var code = (evt.which) ? evt.which : evt.keyCode;

      if(code==8) { // backspace.
        return true;
      } else if((code>=48 && code<=57) || (code >= 65 && code <= 90) || (code >= 97 && code <= 122)) { // is a number.
        return true;
      } else{ // other keys.
        return false;
      }
  }

  window.onload = function() {
     if($('#parts_type').val() != ""){
        fillBrand($('#parts_type').val(), {!!$brands!!});
        var brand_id= "{{ old('brand_id') }}";
        if(brand_id){
          selectionBrand(brand_id);
        }
     }
  };
</script>
  @stop
