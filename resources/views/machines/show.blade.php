@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></a>

        	<div class="row">              

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Owner</label>
                  <input type="text" class="form-control" disabled value="{{$machine->owner->value}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Game Title</label>
                  @if($machine->game_catalog_id != null)
                    <input type="text" class="form-control" disabled value="{{$machine->game->name}}">
                  @else
                    <input type="text" class="form-control" disabled value="">
                  @endif
                </div>
              </div>
              @if($machine->game_catalog_id != null)
                @if($machine->game->description)
                  <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                      <label for="">Description Game</label>
                      <textarea disabled="">{{$machine->game->description}}</textarea>
                    </div>
                  </div>
                @endif
              @endif

              @if($machine->game_catalog_id != null)
                @if($machine->games)
                  <div class="col-12 col-sm-6 col-md-4" >
                    <label for="">Detail Games</label>
                    <div class="form-group">
                      <textarea style="margin-top: 10px" disabled="" id="contained_games_2"></textarea>
                    </div>
                  </div>
                @endif
              @endif

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand</label>
                  @if($machine->brand != null)
                  	<input type="text" class="form-control" disabled value="{{$machine->brand->brand}} {{$machine->brand->model}} {{$machine->brand->weight}}">
                  @else
                  	<input type="text" class="form-control" disabled value="">
                  @endif
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Serial</label>
                  <input type="text" class="form-control" disabled value="{{$machine->serial}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Address</label>
                  @if($machine->address != null)
                  	<input type="text" class="form-control" disabled value="{{$machine->address->client->name}} - {{$machine->address->name_address}}">
                  @else
                  	<input type="text" class="form-control" disabled value="">
                  @endif
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Status</label>
                  @if($machine->status != null)
                  	<input type="text" class="form-control" disabled value="{{$machine->status->value}}">
                  @else
                  	<input type="text" class="form-control" disabled value="">
                  @endif
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Notes</label>
                  <textarea class="form-control" name="notes" disabled>{{$machine->notes}}</textarea>
                </div>
              </div>  

              <div class="col-12 col-sm-6 col-md-4">
                    <div class="form-group">
                      <label for="">Inventory</label>
                      <input type="text" class="form-control @error('inventory') is-invalid @enderror input100 find-inventory" name="inventory" value="{{$machine->inventory}}" style="text-transform:uppercase;" pattern="[A-Za-z0-9]+" disabled>
                    </div>
                  </div>                      

              @if($machine->image)
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Image @if($machine->image) <a href="#" class="btn btn-link view_image" style="width:40px; margin: 0" data-toggle="modal" data-src="{{asset('/images/machines')}}/{{$machine->image}}" data-target="#exampleModalCenter"><i class="far fa-eye"></i></a> @endif </label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    @if($machine->image)
                    <img src="{{asset('/images/machines')}}/{{$machine->image}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                    @endif
                  </div>
                </div>
              </div>
              @endif

              @if(count($machine->parts) > 0)
              <div style="margin-top: 10px;" class="form-group">
                <h3 style="text-align: center">Components</h3>
	              <div style="margin-top: 10px;" class=" table-responsive table-striped table-bordered" >
	                <table id="example" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
	                    <thead>
	                        <tr>
                            <th>ID</th>
	                        	<th>Type</th>
	                        	<th>Brand</th>
	                        	<th>Model</th>
	                        	<th>Serial</th>
                            <th></th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	@foreach($machine->parts as $part)
	                        <tr>
                              <td>{{$part->id}}</td>
	                            <td>{{$part->type->value}}</td>
                              @if($part->brand != null)
	                               <td>{{$part->brand->brand}}</td>
	                               <td>{{$part->brand->model}}</td>
                              @else
                                 <td></td>
                                 <td></td>
                              @endif
	                            <td>{{$part->serial}}</td>
                              @if($part->image != null)
                              <td>
                                <div class="col-4" style="padding: 0;">
                                  <a href="#" class="btn btn-link view_image" style="width:40px; margin: 0" data-toggle="modal" data-src="{{asset('/images/part_brand')}}/{{$part->image}}" data-target="#exampleModalCenter"><i class="far fa-image"></i></a>
                                </div>
                              </td>
                              @else
                                <td></td>
                              @endif
	                        </tr>
	                        @endforeach
	                    </tbody>
	                </table>
	                </div>
	            </div>
              @endif
            </div>
        </div>
  	  </div>
	</div>
</div>




<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="width:70%;">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Actual Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <img src="" id="view_image" alt="">
    </div>
  </div>
</div>

<script>
  $("body").on("click",".view_image",function(){
        $(document.getElementById("view_image")).attr("src",$(this).attr("data-src"));
    });

  window.onload = function() {
    var games = {!!$machine!!}.games;
    if(games){
      var arr1 = games.split("&$");
      var cad_final = "";
      for(var i=0; i< arr1.length; i++){
          if(arr1[i] != ""){
              var arr2 = arr1[i].split('|$');
              cad_final += arr2[0]+" "+arr2[1]+'\n';
          }
      } 
      document.getElementById("contained_games_2").value = cad_final;
    }
  };
</script>

@stop
