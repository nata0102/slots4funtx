@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

        	<a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          	<form class="" action="{{action('ChargesController@update',$charge->id)}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
            	@csrf
            	<input type="hidden" name="_method" value="PUT">
            	<div class="row">	           	
		            <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Utility Calculated:</label>
		                  <input type="text" class="form-control @error('utility_calc') is-invalid @enderror input100" name="utility_calc" id="utility_calc" value="{{$charge->utility_calc}}" readonly="">
		                  @error('utility_calc')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
			        </div>
			        <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Utility S4F <span style="color:red">*</span></label>
		                  <input type="number" id="utility_s4f" class="form-control @error('utilty_s4f') is-invalid @enderror input100" name="utility_s4f" value="{{$charge->utility_s4f}}" required="">
		                  @error('utilty_s4f')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
			        </div>	
			        <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Payment Client <span style="color:red">*</span></label>
		                  <input type="number" class="form-control @error('payment_client') is-invalid @enderror input100" id="payment_client" name="payment_client" value="{{$charge->payment_client}}" required="">
		                  @error('payment_client')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>
			        </div>	

			        <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <input type="button" class="btn btn-info" name="" value="Calculated Average" onclick="putAverage({{@json_encode($charge)}})">
		                </div>
			        </div>	 

			        <div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <input type="button" class="btn btn-info" name="" value="Full Payment" onclick="fullPayment()">
		                </div>
			        </div>	                        
            	</div>
            	<div class="col-12 col-sm-6 col-md-4">
	                <div class="form-group">
	                  <button type="submit" class="btn btn-success">Save</button>
	                </div>
              	</div>
         	</form>
        </div>
      </div>
    </div>
  </div>

<script> 
  $(document).ready(function() {
      
  });

  function putAverage(charge){
  	//console.log(charge);
  	document.getElementById('utility_calc').value = charge.average;
  	document.getElementById('utility_s4f').value = charge.average;
  }

  function fullPayment(){
  	document.getElementById('payment_client').value = document.getElementById('utility_s4f').value;
  }
</script>
@stop
