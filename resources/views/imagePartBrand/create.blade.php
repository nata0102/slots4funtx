@extends('layouts.layout')

@section('content')

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="card" id="card-section">  
          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          <form class="" action="{{action('ImagePartBrandController@store')}}" method="post" enctype="multipart/form-data">
              @csrf    
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Part Type <span style="color:red">*</span></label>
                  <select onchange="fillBrand(this.value, {{$brands}})" id="parts_type" class="form-control selectpicker @error('type') is-invalid @enderror input100" name="type" data-live-search="true" title="-- SELECT TYPE --" required="">
                    @foreach($types as $tp)
                        <option value="{{$tp->id}}" {{isset($_GET['type']) ? $_GET['type'] == $tp->id ?   'selected' : '' : ''}}>{{$tp->value}}</option>
                    @endforeach
                  </select>
                  @error('type')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

               <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">           
                  <label for="">Brands</label>
                  <select class="form-control selectpicker" name="brand" id="parts_brands" data-old="{{old('brand')}}" data-live-search="true" title="-- SELECT BRAND --">
                        <option value="">ALL BRANDS</option>
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
               </div>
              </div>

               <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">           
                  <label for="">Image</label>
                    <div id="preview">
                    </div>

                    <div>
                      <input type="file" class="form-control @error('image') is-invalid @enderror input100" id="inputImagePost" name="image" accept="image/*" required="">
                      @error('image')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">        
                  <button style="margin-left: 50px;margin-top: 25px;" type="submit" class="btn btn-primary">Save</button>
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
    for(var i=0; i < brands.length; i++){
      if(type == brands[i].lkp_id){
        $('#parts_brands').append('<option value="'+brands[i].brand_id+'">'+brands[i].brand+' '+brands[i].model+'</option>');
      }
    }
    $("#parts_brands").selectpicker("refresh");
  }

  function selectionBrand(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#parts_brands option[value='" + e + "']").prop("selected", true);
      });
      $("#parts_brands").selectpicker("refresh");
  }

  window.onload = function() {
    
  };
</script>

@stop
