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
                  <label for="">Role</label>
                  <input type="text" class="form-control" disabled value="{{$res->role->value}}">
                </div>
              </div>

              @if($res->client_id != null)
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="">Client</label>
                    <input type="text" class="form-control" disabled value="{{$res->client->name}}">
                  </div>
                </div>
              @endif

              <div class="col-12 col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" class="form-control" disabled value="{{$res->name}}">
                  </div>
                </div>
              <div class="col-12 col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="">Email</label>
                    <input style="text-transform:lowercase;" type="email" class="form-control" disabled value="{{$res->email}}">
                  </div>
                </div>
              <div class="col-12 col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="">Phone</label>
                    <input type="phone" class="form-control" disabled value="{{$res->phone}}">
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="">Date of Birthday</label>
                    <input type="text" class="form-control" disabled value="{{$res->date_birth}}">
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="">First Day of Work</label>
                    <input type="text" class="form-control" disabled value="{{$res->date_work}}">
                  </div>
                </div>

                @if($res->name_image)
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="form-group">
                    <label for="">Image @if($res->name_image) <a href="#" class="btn btn-link" style="width:40px; margin: 0" data-toggle="modal" data-target="#exampleModalCenter"><i class="far fa-eye"></i></a> @endif </label>
                    <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                      @if($res->name_image)
                      <img src="{{asset('/images/users')}}/{{$res->name_image}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                      @endif
                    </div>
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
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Actual Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @if($res->name_image!=null)
        <div class="modal-body" style="padding: 0;">
          <img src="{{asset('/images/users')}}/{{$res->name_image}}" alt="" style="width: 100%;">
        </div>
      @endif
    </div>
  </div>
</div>


@stop
