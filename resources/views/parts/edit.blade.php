@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          <form class="" action="{{action('PartController@update',$part->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id" value="{{$part->id}}">

            <div class="row">

               <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select onchange="fillBrand(this, {{$brands}})" class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" required>
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
                  <label for="">Brand-Model </label>
                  <select class="form-control @error('brand_id') is-invalid @enderror input100" name="brand_id"  id="parts_brands" required>
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
                  <label for="">Status</label>
                  <select class="form-control @error('lkp_status_id') is-invalid @enderror input100" name="lkp_status_id">
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
                  <input type="text" class="form-control @error('serial') is-invalid @enderror input100" name="serial" value="{{$part->serial}}">
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
                  <label for="">Description</label>
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
    var select = document.getElementById("parts_brands");
    var length = select.options.length;
    for (i = length-1; i >= 0; i--) 
      select.options[i] = null;

    for(var i=0;i < brands.length; i++){      
      if(type.value == brands[i].lkp_part_id){
        var option = document.createElement("option");
        option.value = brands[i].id;
        option.text = brands[i].brand+" - "+brands[i].model;
        option.select = 'selected';
        select.appendChild(option);
      }
    }
  }

  window.onload = function() {
    let select = document.getElementById("parts_brands");
    for(var i=0; i < {!!$brands!!}.length; i++){ 
      if({!!$part->lkp_type_id!!} == {!!$brands!!}[i].lkp_part_id){
          var option = document.createElement("option");
          option.value = {!!$brands!!}[i].id;
          option.text = {!!$brands!!}[i].brand+" - "+{!!$brands!!}[i].model;
          if({!!$part->brand_id!!} == {!!$brands!!}[i].id)
              option.selected = 'selected';
          select.appendChild(option);
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
