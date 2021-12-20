@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          <div class="card" style="margin: 0;" id="gallery" >
            <div class="">
              <div>
                <button data-toggle="modal" data-target="#exampleModalCenter" class="btn btn-primary" style="color: #FFF;">Nueva imagen</button>
              </div>
              <br>
              <div class="row">
                @foreach($images as $image)
                  <div class="col-6 col-sm-3 col-md-3 col-lg-2">
                    <img src="{{asset('images/part brand').'/'.$image->name_image}}" class="img-fluid">
                    <form action="{{action('PartController@deleteImage',$image->id)}}" method="POST">
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
      <form class="" action="{{action('PartController@createImage',$part->id)}}" method="post" enctype="multipart/form-data">
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
