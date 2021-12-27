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
                  <select id="parts_type" onchange="fillBrand(this.value)" class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" data-live-search="true" title="-- Select Type --" required>
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
                  <div hidden>
                    <input  id="old_brand_id" name="old_brand_id" value="{{$part->brand_id}}">
                  </div>
                  <select onchange="setInput(this.value)" class="form-control selectpicker @error('brand_id') is-invalid @enderror input100" name="brand_id"  id="parts_brands" data-live-search="true" title="-- Select Brand --">
                  </select>
                  @error('brand_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="container_details">
                <div class="form-group">
                  <label for="">Details</label>
                  <select class="form-control selectpicker @error('details_ids') is-invalid @enderror input100" name="details_ids[]" id="detail_id" title="-- Select Details --" multiple="multiple">
                  </select>
                  @error('details_ids')
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
                      @if($machine->game !=null)
                        <option  {{ (collect($part->machine->id)->contains($machine->id)) ? 'selected':'' }}  value="{{$machine->id}}">{{$machine->id}} - {{$machine->game->name}} - {{$machine->serial}}</option>
                      @else
                        <option  {{ (collect($part->machine->id)->contains($machine->id)) ? 'selected':'' }}  value="{{$machine->id}}">{{$machine->id}} - {{$machine->serial}}</option>
                      @endif
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
  function fillBrand(type){
    var brands = {!!$brands!!};
    $('#parts_brands').empty();
    $('#parts_brands').append('<option value=""></option>');
    for(var i=0; i < brands.length; i++){
      if(type == brands[i].lkp_id){
        $('#parts_brands').append('<option value="'+brands[i].id+'">'+brands[i].brand+' '+brands[i].model+'</option>');
      }
    }
    $("#parts_brands").selectpicker("refresh");

    var details = {!!$details!!};
    $('#detail_id').empty();
    for(var i=0; i < details.length; i++){
      if(type == details[i].part_id){
        $('#detail_id').append('<option value="'+details[i].id+'">'+details[i].value+'</option>');
      }
    }
    if($('#detail_id option').length == 0)
      document.getElementById("container_details").hidden=true;
    else
      document.getElementById("container_details").hidden=false;
    $("#detail_id").selectpicker("refresh");
  }

  function setInput(value){
    document.getElementById('old_brand_id').value=value;
  }

  function selectionPart(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#parts_type option[value='" + e + "']").prop("selected", true);
      });
      $("#parts_type").selectpicker("refresh");
  }

  function selectionBrand(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#parts_brands option[value='" + e + "']").prop("selected", true);
      });
      $("#parts_brands").selectpicker("refresh");
  }

  function selectionDetails(){
    $details_ids = {!!$details_aux_ids!!};
    for(var i=0; i<$details_ids.length; i++){
      var arr = [$details_ids[i]];
      $.each(arr, function(i,e){
         $("#detail_id option[value='" + e + "']").prop("selected", true);
      });
    }
    $("#detail_id").selectpicker("refresh");
  }

  $(document).ready(function() {
    var p_type = document.getElementById('parts_type');
    selectionPart(p_type.value);   
    fillBrand(p_type.value); 
    selectionBrand(document.getElementById('old_brand_id').value);
    $("#machines").selectpicker("refresh");  

    $details_ids = {!!$details_aux_ids!!};
    selectionDetails(); 
  });

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
