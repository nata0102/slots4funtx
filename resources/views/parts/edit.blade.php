@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          <form class="" action="{{action('PartController@update',$part->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id" value="{{$part->id}}">

            <div class="row">

               <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select id="parts_type" onchange="fillBrand(this.value, {{$brands}})" class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" data-live-search="true" title="-- Select Type --" required>
                    <option value=""></option>
                    @foreach($types as $type)
                      <option value="{{$type->id}}" {{$part->lkp_type_id == $type->id ? 'selected' : ''}}>{{$type->value}}</option>
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
                  <select class="form-control selectpicker @error('brand_id') is-invalid @enderror input100" name="brand_id"  id="parts_brands" data-live-search="true" title="-- Select Brand --">
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
                      <option value="{{$protocol->id}}" {{$part->lkp_protocol_id == $protocol->id ? 'selected' : ''}}>{{$protocol->value}}</option>
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
                  <label for="">Status <span style="color:red">*</span></label>
                  <select class="form-control @error('lkp_status_id') is-invalid @enderror input100" name="lkp_status_id" required>
                    <option value=""></option>
                    @foreach($status as $status)
                      <option value="{{$status->id}}" {{$part->lkp_status_id == $status->id ? 'selected' : ''}}>{{$status->value}}</option>
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
                  <label for="">Serial</label>
                  <input type="text" class="form-control @error('serial') is-invalid @enderror input100 find-serial" style="text-transform:uppercase;" pattern="[A-Za-z0-9]+" name="serial" value="{{$part->serial}}">
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
                  <input type="text" class="form-control @error('price') is-invalid @enderror input100" name="price" value="{{$part->price}}">
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
                  <select class="form-control selectpicker show-menu-arrow @error('parts') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Machine --" name="machine_id">
                  @foreach($machines as $machine)
                    @if($part->machine_id != NULL)
                    <option  {{ (collect($part->machine->id)->contains($machine->id)) ? 'selected':'' }}  value="{{$machine->id}}">{{$machine->id}} - {{$machine->game->value}} - {{$machine->serial}}</option>
                    @else
                    <option  {{ (collect(old('machine_id'))->contains($machine->id)) ? 'selected':'' }}  value="{{$machine->id}}">{{$machine->id}} - {{$machine->serial}}</option>
                    @endif
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12">
                <div class="form-group">
                  <label for="">Notes</label>
                  <textarea name="description" class="form-control @error('description') is-invalid @enderror input100" rows="8" cols="80" style="width: 100%; height:  5rem;">{{$part->description}}</textarea>
                  @error('description')
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

  window.onload = function() {
     if($('#parts_type').val() != ""){
        fillBrand($('#parts_type').val(), {!!$brands!!});

        var brand_id= "{{old('brand_id')}}";
        if(brand_id=="")
          brand_id = "{{$part->brand_id}}";
        if(brand_id){
          selectionBrand(brand_id);
        }
     }
  };

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
</script>
  @stop
