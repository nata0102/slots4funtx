@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          <form class="" action="{{action('MachineBrandController@update',$brand->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id" value="brand->id}}">

            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select id="machine_brand_type" class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" required="">
                    <option value=""></option>
                      @foreach($types as $type)
                        <option value="{{$type->id}}"  {{ $brand->lkp_type_id == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_type_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="combo-content" hidden>
                <div class="form-group">
                  <label for="">Parts <span style="color:red"></span></label>
                  <select class="form-control" name="" required="" id="combo-select">
                    <option value="" selected disabled>-- Select Part --</option>
                    @foreach($parts as $part)
                      <option value="{{$part->id}}" {{ $brand->lkp_part_id == $part->id ? "selected" : '' }}>{{$part->value}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('brand') is-invalid @enderror input100" name="brand" value="{{$brand->brand}}" required>
                  @error('brand')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Model</label>
                  <input type="text" class="form-control @error('model') is-invalid @enderror input100" name="model" value="{{$brand->model}}">
                  @error('model')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Weight</label>
                  <input type="text" class="form-control @error('weight') is-invalid @enderror input100" name="weight" value="{{$brand->weight}}">
                  @error('weight')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

            </div>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <button type="submit" class="btn btn-success">Save</button>
                </div>
              </div>
          </form>


          <div class="card" style="margin: 0;">
            <div class="">
              <div>
                <button data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary" style="color: #FFF;">Nueva imagen</button>
              </div>
              <br>
              <div class="row">
                @foreach($brand->images as $image)
                  <div class="col-6 col-sm-3 col-md-3 col-lg-2">
                    <img src="{{asset('images/part brand').'/'.$image->name_image}}" class="img-fluid">
                    <form action="{{action('MachineBrandController@deleteImage',$image->id)}}" method="POST">
                      @csrf
                      <input name="_method" type="hidden" value="DELETE">
                      <button type="submit" class="btn btn-danger" style="left: 15px; top: 0; position: absolute;"><i class="far fa-trash-alt"></i></button>
                    </form>
                  </div>
                @endforeach
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
        <h5 class="modal-title" id="exampleModalLongTitle">Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form class="" action="{{action('MachineBrandController@createImage',$brand->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        <div id="preview">

        </div>

        <div class="modal-body">
          <input type="file" class="" id="inputImagePost" name="image" accept="image/*" >
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  document.getElementById("inputImagePost").onchange = function(e) {
  // Creamos el objeto de la clase FileReader
  let reader = new FileReader();

  // Leemos el archivo subido y se lo pasamos a nuestro fileReader
  reader.readAsDataURL(e.target.files[0]);

  // Le decimos que cuando este listo ejecute el código interno
  reader.onload = function(){
    let preview = document.getElementById('preview'),
            image = document.createElement('img');

    image.src = reader.result;

    preview.innerHTML = '';
    preview.append(image);
  };
  }
</script>


  @stop
