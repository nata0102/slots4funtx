@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

        	<a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

        	<div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Folio: {{$invoice->folio}}</label>
                </div>
                <div class="form-group">
                  <label for="">Client - Bussines: {{$invoice->client->name}} - {{$invoice->address->business_name}}</label>
                </div>
            </div>
          	<form class="" action="{{action('InvoiceController@update',$invoice->id)}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
          		
            	@csrf
            	<input type="hidden" name="_method" value="PUT">
            	<div class="row">

	            	<div class="col-12 col-sm-6 col-md-4">
		                <div class="form-group">
		                  <label for="">Type <span style="color:red">*</span></label>
		                  <select class="form-control @error('lkp_type_id') is-invalid @enderror input100" name="lkp_type_id" id="lkp_type_id">
		                    <option value=""></option>
		                      @foreach($types as $type)
		                        <option value="{{$type->id}}">{{$type->value}}</option>
		                      @endforeach
		                  </select>
		                  @error('lkp_owner_id')
		                      <span class="invalid-feedback" role="alert">
		                          <strong>{{ $message }}</strong>
		                      </span>
		                  @enderror
		                </div>

		                <div class="col-12 col-sm-6 col-md-4">
			                <div class="form-group">
			                  <label for="">Description</label>
			                  <textarea id="description" name="description"></textarea>
			                </div>
			            </div>

			            <div class="col-12 col-sm-6 form-group">
		                  <label for="">Payment Client <span style="color:red">*</span></label>
		                  <input class="form-control" id="payment_client" step="any" min="0" type="number" name="payment_client" 
		                  max="{{$invoice->total_discount-$invoice->payment_client}}"
		                  value="{{$invoice->total_discount-$invoice->payment_client}}" required style="width: 100%;">
		                </div>
		            </div>

		            <div class="col-4" style="margin-top: 30px">
	                    <button type="button" id="buttonAdd" onclick="addPaymentToTable()" name="button" class="btn btn-info">+</button>
	                </div>           

			            <div style="margin-top: 10px;" class="form-group">
			                <h3 style="text-align: center">Payments</h3>
			                <div style="margin-top: 10px;" class=" table-responsive table-striped table-bordered" >
			                    <table id="table_components" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
			                      <thead>
			                          <tr>
			                            <th>ID</th>
			                            <th>Type</th>
			                            <th>Description</th>
			                            <th>Amount</th>
			                            <th></th>
			                          </tr>
			                      </thead>
			                      <tbody>
			                      	<?php 
			                      		$i = 0;
			                      	?>
			                      	@foreach($payments as $payment)
			                      		
			                      		<tr id="td_{{$i}}">
				                            <td>{{$payment->id}}</td>
				                            <td>{{$payment->type}}</td>
				                            <td>{{$payment->description}}</td>
				                            <td>{{$payment->amount}}</td>
				                            <td><div class="row" style="margin-right: 0; margin-left: 0;"><div class="col-4 active" style="padding: 0;"><button onclick="deleteRow({{$i}}, {{$payment->amount}})" class="btn btn-link" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button></div></div></td>
				                        </tr>
				                        <?php
				                        	$i++;
				                        ?>
			                      	@endforeach			                       
			                      </tbody>
			                    </table>
			                </div>
			            </div>
            	</div>
            	<div style="margin-top: 10px;" class="col-12">
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
  	function insertRow(cad_id, val1, val2, val3){
      var table = document.getElementById(cad_id);
      var rowCount = table.rows.length;

      var cad='<tr id="td_'+rowCount+'">';
      cad+='<td><input type="hidden" name="tab_id[]" value=""></td>';
      cad+='<td><input type="hidden" name="tab_type[]" value="'+val1+'">' + val1 + '</td>'
      cad+='<td><input type="hidden" name="tab_description[]" value="'+val2+'">' + val2 + '</td>';
      cad+='<td><input type="hidden" name="tab_amount[]" value="'+val3+'">'+val3+'</td>';
      cad+='<td><div class="row" style="margin-right: 0; margin-left: 0;"><div class="col-4 active" style="padding: 0;"><button onclick="deleteRow('+rowCount+', '+val3+')" class="btn btn-link" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button></div></div></td></tr>';

       $("#"+cad_id+" tbody").append(cad); 
    }

  	function addPaymentToTable(){
  		var description = $('#description').val(); 
  		var lkp_type_id = $('#lkp_type_id').val(); 
  		var lkp_type_value = $( "#lkp_type_id option:selected" ).text();
  		var payment_client = $('#payment_client').val(); 
  		var payment_max = $('#payment_client').attr('max');

  		if(payment_client > 0 && lkp_type_id != ""){ 		
	  		insertRow('table_components', lkp_type_value, description, payment_client);
	  		//Seteando valores
	  		var total_faltante = payment_max-payment_client;		  		
	  		$('#payment_client').attr({"max" : total_faltante});
	  		$("#payment_client").val(total_faltante);
		    $("#description").val("");
			$("#lkp_type_id").val(0);
			if(total_faltante == 0)
				$( "#buttonAdd" ).prop( "disabled", true );
		}else
			toastr.error('Complete fields.', '', {timeOut: 3000});
  	}

  	function deleteRow(row_tr, val){
  		var element = document.getElementById("td_"+row_tr);
      	element.parentNode.removeChild(element);
      	var payment_client = $('#payment_client').val(); 
      	var total_faltante = parseFloat(val)+parseFloat(payment_client);
      	$('#payment_client').attr({"max" : total_faltante});
  		$("#payment_client").val(total_faltante);
  		if(total_faltante > 0)
			$( "#buttonAdd" ).prop( "disabled", false );
  	}
  </script>
  @stop
