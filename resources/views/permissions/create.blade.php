@extends('layouts.layout')

@section('content')


<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="card" id="card-section">

        <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

        <div>
          <div align="center" style="margin-top: 10px;-ms-transform: translateY(-50%);
                transform: translateY(-50%);">
                <button align="center" id="boton_qr" hidden class="btn btn-info" style="width: 40px; height: 40px;" onclick="readQR()"><i class="fas fa-qrcode"></i></button>
          </div>
          <div align="center" id="div_cam" hidden>
              <video align="center" id="preview" width="50%"></video>
              <input type="text" name="text" id="text_qr">
          </div>
        </div>

        <form class="" action="{{action('PermissionController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type Permit <span style="color:red">*</span></label>
                  <select onchange="fillMachines(this.value)"  id="permit_type" class="form-control @error('lkp_type_permit_id') is-invalid @enderror input100" name="lkp_type_permit_id" required="">
                    <option value="">-- Select Type Permit --</option>
                      @foreach($types as $type)
                        <option value="{{$type->id}}"  {{ old('lkp_type_permit_id') == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_type_permit_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Machine <span style="color:red">*</span></label>
                  <select id="permission_machine" class="form-control selectpicker @error('machine_id') is-invalid @enderror input100" title="-- Select Machine --" name="machine_id" required="" data-live-search="true" >
                  </select>
                  @error('machine_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Permit Number <span style="color:red">*</span></label>
                  <input type="text" id="long" pattern="[0-9]{6}" class="form-control @error('permit_number') is-invalid @enderror input100" name="permit_number" value="{{old('permit_number')}}" required="">
                  @error('permit_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Validate Permit Number <span style="color:red">*</span></label>
                  <input id="long2" type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="form-control @error('validate_permit_number') is-invalid @enderror input100" name="validate_permit_number" value="{{old('validate_permit_number')}}" required="" onpaste="return false;">
                  @error('validate_permit_number')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" >
                <div class="form-group">
                  <label for="">Permit Year <span style="color:red">*</span></label>
                  <input id="year_permit" type="number" name="year_permit" min="2021" max="2035"
                  class="form-control @error('year_permit') is-invalid @enderror input100" value="{{old('year_permit')}}" required>
                  @error('year_permit')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
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

<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

<script>

  $('#year_permit').on('input', function() {
        console.log("entre jquery");
        changeYear();
  });

  function readQR(){
      let aux = document.getElementById('div_cam');
      aux.removeAttribute("hidden");
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview')});
      Instascan.Camera.getCameras().then(function(cameras){
        if(cameras.length > 0){
          if (cameras[1]) {
              //use that by default
              scanner.start(cameras[1]);
          } else {
              //else use front camera
              scanner.start(cameras[0]);
          }
        }
        else
          alert("No cameras");
      }).catch(function(e){
      console.error(e);
      });

      scanner.addListener('scan', function(c){
          document.getElementById("div_cam").hidden = true;
          var arr = c.split("/");
          selectMachine(arr[1]);
      });
  }

  function selectMachine(value_id){
        var select_machine = document.getElementById("permission_machine").options;
        for(var i =0; i< select_machine.length;i++){
            if(select_machine[i].value == value_id){
              $('.selectpicker').selectpicker('val', value_id);
              $("#permission_machine").selectpicker("refresh");
              break;
            }
        }
  }

  function fillMachines(type){
    var year = document.getElementById("year_permit").value;
    if(type!="")
        document.getElementById("boton_qr").hidden = false;
    else
        document.getElementById("boton_qr").hidden = true;

    $('#permission_machine').empty();
    for(var i=0; i < {!!$machines!!}.length; i++){
          var band = true;
          for(var j=0; j<{!!$machines!!}[i].permission.length;j++){
             if({!!$machines!!}[i].permission[j].lkp_type_permit_id == type && 
              {!!$machines!!}[i].permission[j].year_permit == year){
                band=false;
                break;
             }
          }
          if(band){
            var name_aux = "";
            name_aux += {!!$machines!!}[i].id+" - "+ {!!$machines!!}[i].owner.value;
            if({!!$machines!!}[i].game != null)
              name_aux +=  ' - ' + {!!$machines!!}[i].game.name;
            name_aux += " - "+{!!$machines!!}[i].serial;
            $('#permission_machine').append('<option value="'+{!!$machines!!}[i].id+'">'+name_aux+'</option>');
          }
    }
    $("#permission_machine").selectpicker("refresh");
  }

  function changeYear(){
    console.log()
    var comp_permit_type = document.getElementById("permit_type");
    if(comp_permit_type.value !="")
        fillMachines(comp_permit_type.value);
  }

  window.onload = function() {
    const d = new Date();
    document.getElementById("year_permit").value = d.getFullYear();
    var comp_permit_type = document.getElementById("permit_type");
    if(comp_permit_type.value !=""){
        fillMachines(comp_permit_type.value);
        selectMachine("{{old('machine_id')}}");
    }
  };
  </script>

@stop
