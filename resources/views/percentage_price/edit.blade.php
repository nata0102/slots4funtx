@extends('layouts.layout')

@section('content')

<div class="main-content">
	<div class="section__content section__content--p30">
  		<div class="container-fluid">
    		<div class="card" id="card-section">

        		<a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          		<form class="" action="{{action('PercentagePriceController@update',$percentage_price->id)}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
            	@csrf
            		<input type="hidden" name="_method" value="PUT">
            		<div class="row">

			            <div class="col-12 col-sm-6 col-md-4">
			                <div class="form-group">
			                  <label for="">Type <span style="color:red">*</span></label>
			                  <select id="percentage_type" class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" required="">
			                    <option value=""></option>
			                      @foreach($types as $type)
			                        <option value="{{$type->id}}"  {{ $percentage_price->lkp_type_id == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
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
			                  <label disabled="disabled" for="">Machine <span style="color:red">*</span></label>
			                  <input disabled="disabled" class="form-control" value="{{$machine->id}} - {{$machine->owner->value}} - {{$machine->game->name}} - {{$machine->serial}}">
			                </div>
			            </div>

			            <div class="col-12 col-sm-6 col-md-4">
			                <div class="form-group">
			                  <label for="">Payment Periodicity <span style="color:red">*</span></label>
			                  <select id="percentage_periodicity" class="form-control @error('lkp_periodicity_id') is-invalid @enderror input100" name="lkp_periodicity_id" required="">
			                    <option value=""></option>
			                      @foreach($payments as $type)
			                        <option value="{{$type->id}}"  {{ $percentage_price->lkp_periodicity_id == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
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
			                  class="form-control @error('payday') is-invalid @enderror input100" value="{{$percentage_price->payday}}" required>
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
			                  <input type="number" class="form-control @error('amount') is-invalid @enderror input100" name="amount" value="{{$percentage_price->amount}}">
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
			                  <input type="date" class="form-control @error('date_permit') is-invalid @enderror input100" name="start_payment" value="{{$percentage_price->start_payment}}" id="datepicker" required="">
			                  @error('start_payment')
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
@stop
