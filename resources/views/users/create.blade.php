@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-long-arrow-alt-left"></i></a>

          <form class="" action="{{action('UserController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">             

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Roles <span style="color:red">*</span></label>
                  <select class="form-control @error('lkp_rol_id') is-invalid @enderror input100" name="lkp_rol_id" id="lkp_rol_id" required=""  onchange="disableClients()">
                      <option value=""></option>
                      @foreach($roles as $r)
                        <option value="{{$r->id}}"  {{ old('lkp_rol_id') == $r->id ? 'selected' : '' }}>{{$r->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_rol_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="client">
                <div class="form-group">
                  <label for="">Clients <span style="color:red">*</span></label>
                  <select class="form-control @error('client_id') is-invalid @enderror input100" name="client_id">
                      <option value=""></option>
                      @foreach($clients as $r)
                        <option value="{{$r->id}}"  {{ old('client_id') == $r->id ? 'selected' : '' }}>{{$r->name}}</option>
                      @endforeach
                  </select>
                  @error('client_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Name <span style="color:red">*</span></label>
                  <input style="text-transform: uppercase;" type="text" class="form-control @error('name') is-invalid @enderror input100" required name="name" value="{{old('name')}}">
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Email <span style="color:red">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror input100" name="email" value="{{old('email')}}" style="text-transform:lowercase;" required="">
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

               <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Phone <span style="color:red">*</span></label>
                  <input type="number" class="form-control @error('phone') is-invalid @enderror input100" name="phone" value="{{old('phone')}}"  pattern="[0-9]+" required="">
                  @error('phone')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Password <span style="color:red">*</span></label>
                  <div class="input-group">
                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror input100" name="password" value="{{old('password')}}" style="text-transform:none;"  required="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary far fa-eye" type="button" onclick="mostrarContrasena()"></button>
                    </div>
                  </div>
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

             <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Date of Birthday</label>
                  <input type="date" class="form-control @error('date_birth') is-invalid @enderror input100" name="date_birth" value="{{old('date_birth')}}">
                  @error('date_birth')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">First Day of Work</label>
                  <input type="date" class="form-control @error('date_work') is-invalid @enderror input100" name="date_work" value="{{old('date_work')}}">
                  @error('date_work')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Image</label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    <img src="{{asset('/images/interface.png')}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                  </div>
                  <input class="photo" type="file" name="image" value="{{old('image')}}" id="img-btn-3" data-id2="img-new-3" accept="image/*" hidden>
                  <input class="mg" type="text" value="" id="img-new-3" accept="image/*" hidden>
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

function mostrarContrasena(){
      var tipo = document.getElementById("password");
      if(tipo.type == "password"){
          tipo.type = "text";
      }else{
          tipo.type = "password";
      }
  }

 $(document).ready(function() { 
      document.getElementById("client").hidden=true; 
      if(document.getElementById("lkp_rol_id").value != null)    
        disableClients();
           
  });

 function disableClients(){
    var role_id = document.getElementById("lkp_rol_id").value;
    document.getElementById("client").hidden=true;
    var roles = {!!$roles!!};
    for(var i=0;i<roles.length;i++){
        if(role_id == roles[i].id){
            if(roles[i].key_value=='client')
              document.getElementById("client").hidden=false; 
             
        }
    }
 }

  

</script>
  @stop
