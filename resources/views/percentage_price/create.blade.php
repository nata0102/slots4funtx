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

        <form class="" action="{{action('PercentagePriceController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select onchange="activateQR(this.value)" id="percentage_type" class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" required="">
                    <option value=""></option>
                      @foreach($types as $type)
                        <option value="{{$type->id}}"  {{ old('lkp_type_id') == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
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
                  <label for="">Machine <span style="color:red">*</span></label>
                  <select id="permission_machine" class="form-control selectpicker @error('machine_id') is-invalid @enderror input100" name="machine_id" required="" data-live-search="true">
                      <option value="" selected>-- Select Machine --</option>
                      @foreach($machines as $machine)
                        <option value="{{$machine->id}}"  {{ old('machine_id') == $machine->id ? 'selected' : '' }}>{{$machine->id}} - {{$machine->owner}} - {{$machine->game}} - {{$machine->serial}}</option>
                      @endforeach
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
                  <label for="">Payment Periodicity <span style="color:red">*</span></label>
                  <select id="percentage_periodicity" class="form-control @error('lkp_periodicity_id') is-invalid @enderror input100" name="lkp_periodicity_id" required="">
                    <option value=""></option>
                      @foreach($payments as $type)
                        <option value="{{$type->id}}"  {{ old('lkp_periodicity_id') == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_periodicity_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="content_percentage_payday" hidden>
                <div class="form-group">
                  <label for="">Payday <span style="color:red">*</span></label>
                  <input type="number" id="percentage_payday" name="payday" min="1" max="31"
                  class="form-control @error('payday') is-invalid @enderror input100" value="{{old('payday')}}" required>
                  @error('payday')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="" id="input">Percentage/Amount <span style="color:red">*</span></label>
                  <input type="number" class="form-control @error('amount') is-invalid @enderror input100" name="amount" value="{{old('amount')}}" required="">
                  @error('amount')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                      <div class="form-group">
                        <label for="">Start Payment <span style="color:red">*</span></label>
                        <input type="date" class="form-control @error('date_permit') is-invalid @enderror input100" name="start_payment" value="{{old('')}}" id="datepicker" required="">
                        @error('start_payment')
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

  function activateQR(type){
    if(type!="")
        document.getElementById("boton_qr").hidden = false;
    else
        document.getElementById("boton_qr").hidden = true;
  }

  window.onload = function() {
    var comp_type = document.getElementById("percentage_type");
    if(comp_type.value !=""){
        activateQR(comp_type.value);
    }
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
    $('#datepicker').val(today);
  };
  </script>

@stop
