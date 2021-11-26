@extends('layouts.layout')

@section('content')

<div class="main-content">
	<div class="section__content section__content--p30">
  		<div class="container-fluid">
    		<div class="card" id="card-section">

        		<a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

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

          		<form class="" action="{{action('PermissionController@update',$permission->id)}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
            	@csrf
            		<input type="hidden" name="_method" value="PUT">
            		<div class="row">

			            <div class="col-12 col-sm-6 col-md-4">
			                <div class="form-group">
			                  <label for="">Type Permit <span style="color:red">*</span></label>
			                  <select disabled="disabled" id="permit_type" class="form-control @error('lkp_type_permit_id') is-invalid @enderror input100" name="lkp_type_permit_id">
			                    <option value=""></option>
			                      @foreach($types as $type)
			                        <option value="{{$type->id}}"  {{ $permission->lkp_type_permit_id == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
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
			                  @if($permission->machine_id == null)
									<select id="permission_machine" class="form-control selectpicker @error('machine_id') is-invalid @enderror input100" required name="machine_id" data-live-search="true">
										 <option value="" selected >-- Select Machine --</option>
										 @foreach($machines as $machine)
											 <option value="{{$machine->id}}"  >{{$machine->id}} - {{$machine->owner}} - {{$machine->game}} - {{$machine->serial}}</option>
										 @endforeach
								 </select>
								 @error('machine_id')
										 <span class="invalid-feedback" role="alert">
												 <strong>{{ $message }}</strong>
										 </span>
								 @enderror
				              @else
									<input disabled="disabled" class="form-control @error('machine_id') is-invalid @enderror input100" value="{{$permission->machine_id}} - {{$permission->machine->owner->value}} - {{$permission->machine->game->name}} - {{$permission->machine->serial}}">
			                  @endif
			                </div>
			            </div>

			            <div class="col-12 col-sm-6 col-md-4">
			                <div class="form-group">
			                  <label for="">Permit Number <span style="color:red">*</span></label>
			                  <input type="text" id="long" pattern="[0-9]{6}" class="form-control @error('permit_number') is-invalid @enderror input100" name="permit_number" value="{{$permission->permit_number}}">
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
			                  <input type="text" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="6" class="form-control @error('validate_permit_number') is-invalid @enderror input100" name="validate_permit_number" value="{{$permission->permit_number}}" required="" onpaste="return false;">
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
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
function readQR(){
      let aux = document.getElementById('div_cam');
      aux.removeAttribute("hidden");
      let scanner = new Instascan.Scanner({ video: document.getElementById('preview')});
      Instascan.Camera.getCameras().then(function(cameras){
        if(cameras.length > 0)
          scanner.start(cameras[0]);
        else
          alert("No cameras");
      }).catch(function(e){
      console.error(e);
      });

      scanner.addListener('scan', function(c){
          //document.getElementById('text_qr').value = c;
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

window.onload = function() {
	const d = new Date();
    document.getElementById("year_permit").value = d.getFullYear();
	var comp_machine = document.getElementById("permission_machine");
	if(comp_machine != null){
		document.getElementById("boton_qr").hidden = false;
	} 
  };
</script>
@stop
