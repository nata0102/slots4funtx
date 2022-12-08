@extends('layouts.layout')

@section('content')

  <div class="main-content" >
    <div class="section__content section__content--p30">
      <div class="container-fluid">

        <form class="" action="" method="post" id="form">
          @csrf


          <div class="card" id="card-section">
            <div class="row">
              <div class="col-12 col-sm-4 form-group">
                <label for="">Type <span style="color:red">*</span></label>
                <select class="form-control selectpicker" name="type" style="width: 100%;" id="type" data-live-search="true" required onchange="showForm(this)">
                  <option value="" selected disabled>-- Select Type --</option>
                  @foreach($types as $type)
                    <option value="{{$type->key_value}}" {{ $data ? $data["type"] == $type->key_value ? "selected":"" : ''}}>{{$type->value}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!---------------------------- FLAT RATE --------------------->

            <div id="div_clients_flat_rate" class="row" hidden="">
              <div class="col-12 col-sm-4 form-group">
                <label for="">Client <span style="color:red">*</span></label>
                <select class="form-control selectpicker" id="client_flat_rate" name="client_flat_rate" style="width: 100%;" data-live-search="true" onchange="fillMachinesFlatRate(this)">
                  <option value="" selected disabled>-- Select Client-Business --</option>
                  @foreach($clients_flat_rate as $client)
                    <option value="{{$client->id}}" data-address_id="{{$client->address_id}}" {{ $data ? $data["client"] == $client->id ? "selected":"" : ''}}>{{$client->name}} - {{$client->business_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>  

            <input type="hidden" name="client_address_id" value="" id="address_id">      

            <div id="div_machines_flat_rate" class="row" hidden="">
              <div class="col-12 col-sm-4 form-group">
                <label for="">Machines <span style="color:red">*</span></label>
                <select class="form-control selectpicker" id="machine_flat_rate" style="width: 100%;" data-live-search="true" multiple="multiple" name="machines_ids[]" onchange="sumMachinesFlatRate()">
                  <option value="" selected disabled>-- Select Machines --</option>
                </select>
              </div>
            </div>
            
            <!------------------------------- CHARGES --------------------------->

            <div id="form_charges" hidden="">  

              <div class="row">
                <div class="col-12 col-sm-4 form-group">
                  <label for="">Client <span style="color:red">*</span></label>
                  <select class="form-control selectpicker" id="client" name="client_id" style="width: 100%;" data-live-search="true" onchange="showFieldsCharges(this)">
                    <option value="" selected disabled>-- Select Client-Business --</option>
                    @foreach($clients as $client)
                      <option value="{{$client->id}}" data-address_id="{{$client->address_id}}" {{ $data ? $data["client"] == $client->id ? "selected":"" : ''}}>{{$client->name}} - {{$client->business_name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>           

              <div class="row">
                <div class="col-12 col-sm-3 form-group">
                  <label for="">From</label>
                  <input class="form-control" type="date" name="from" value="{{ $data ? $data['from'] : ''}}" id="from">
                </div>
                <div class="col-12 col-sm-3 form-group">
                  <label for="">To</label>
                  <input class="form-control" type="date" name="to" value="{{ $data ? $data['to'] : ''}}" id="to">
                </div>
                <div class="col-12 col-sm-1 form-group">
                  <button type="button" class="btn" style="margin-top: 37px;" id="search" onclick='searchF()'><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </div>

              <div class="row">
                <div class="col-12 col-sm-4 form-group">
                  <div class="" id="machines">
                    <div class="" id="select_content" hidden>
                      <label for="">Charges <span style="color:red">*</span></label>
                      <select class="form-control selectpicker" data-live-search="true" multiple="multiple" name="charges_ids[]" id="charge_machine" title="SELECT - CHARGES" onchange="calculateF(this.id)">
                        @foreach($machines as $machine)
                          <option value="{{$machine->id}}" data-s4f="{{$machine->utility_s4f}}" data-calc="{{$machine->utility_calc}}">ID:{{$machine->id}} | DATE:{{$machine->date_charge}} | M:{{$machine->name_machine}} | UC:{{$machine->utility_calc}} | US4F:{{$machine->utility_s4f}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="" id="input" hidden>
              <div class="row">
                <div class="col-4">
                  <label id="label_total" style="font-weight: bold" for="">Total System:</label>
                  <label>$</label><label id="total_calculated_label" name="utility">0.00</label>
                  <input type="hidden" name="total_invoice" value="" id="total_calculated">
                </div>
                <div class="col-4">
                  <label id="label_modified" style="font-weight: bold" for="">Total S4F:</label>
                  <label>$</label><label value="" id="total_modified_label">0.00</label>

                  <input type="hidden" value="" id="total_modified_aux">
                  <input type="hidden" name="total_invoice_modified" value="" id="total_modified">
                </div>
              </div>

              <div class="row">

                <div class="col-12 col-sm-4 form-group">
                  <label for="">Discount %</label>
                  <input class="form-control" id="discount" min="0" step="any" max="100" type="number" name="discount" value="0" required style="width: 100%;" onchange="totalDiscountF(this)" onkeyup="this.onchange();">
                </div>

                <div class="col-12 col-sm-4 form-group">
                  <label for="">Payment Client</label>
                  <input class="form-control" id="payment_client" step="any" min="0" type="number" name="payment_client" value="0" required style="width: 100%;">
                </div>

                <div class="col-12 form-group">
                  <button type="button" class="btn btn-success" id="save" onclick='saveF()'>Save</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@stop
<script>
  search = "{{action('InvoiceController@machines')}}";
  store = "{{action('InvoiceController@store')}}";
  div_refresh = "machines";
  form_id = "form";


  function showForm(option){ 
    $( "#div_clients_flat_rate" ).prop("hidden", true);
    $( "#div_machines_flat_rate" ).prop("hidden", true);
    $( "#form_charges" ).prop("hidden", true);
    $( "#input" ).prop( "hidden", true );
   
    switch(option.value){
      case 'flat_rate_invoice':
        document.querySelector('#client_flat_rate').value = '';
        $( "#div_clients_flat_rate" ).prop( "hidden", false );
      break;
      case 'charges':
        document.querySelector('#client').value = '';
        $( "#form_charges" ).prop("hidden", false);
        resetFildsCharges();
      break;
    }
    $('.selectpicker').selectpicker('refresh');

  }

  function fillMachinesFlatRate(option){
    $( "#div_machines_flat_rate" ).prop("hidden", false);
    $("#input").prop( "hidden", false );
    $("#machine_flat_rate").empty();
    document.getElementById("address_id").value = option.options[option.selectedIndex].getAttribute("data-address_id");
    resetAmounts();

    var machines = {!! json_encode($machines_flat_rate) !!};

    machines.forEach(function(element){ 
      var cad = "M:"+element.id+" - "+element.serial+' - '+element.name_game;
      cad += " | Periodicity:"+element.periodicity + " | Amount:"+element.amount;
      $('#machine_flat_rate').append($("<option></option>").attr("data-amount",element.amount).attr("value", element.id).text(cad)); 
    });
    $("#machine_flat_rate").selectpicker("refresh");   
    
  }

  function sumMachinesFlatRate(){
    var sum = 0;
    $("#machine_flat_rate :selected").map(function(i, el) {
      sum += parseFloat(el.getAttribute('data-amount'));      
    }).get();

    document.getElementById("total_calculated").setAttribute('value',sum.toFixed(2));
    document.getElementById("total_modified_aux").setAttribute('value',sum.toFixed(2));
    document.getElementById("total_calculated_label").innerHTML = sum.toFixed(2);
    document.getElementById("total_modified_label").innerHTML = sum.toFixed(2);
    totalDiscountF();
  }

  function resetAmounts(){    
    document.getElementById("total_modified_aux").setAttribute('value',"");
    document.getElementById("total_calculated_label").innerHTML = "0.00";
    document.getElementById("total_modified_label").innerHTML = "0.00";
    document.getElementById("payment_client").setAttribute('value', 0);
    document.getElementById('discount').value = 0;

    document.getElementById("total_calculated").setAttribute('value',"");
    document.getElementById("total_modified_aux").setAttribute('value',"");
  }

  function totalDiscountF(){
    discount = document.getElementById('discount').value;
    total_invoice = document.getElementById('total_modified_aux').value;
    total = total_invoice - total_invoice*(discount/100);

    document.getElementById('total_modified').value = total.toFixed(2);
    document.getElementById("payment_client").setAttribute('value',total.toFixed(2));
    document.getElementById("payment_client").setAttribute('max',total.toFixed(2));
    document.getElementById("total_modified_label").innerHTML = total.toFixed(2);
  }

  function resetFildsCharges(){    
    document.getElementById("select_content").setAttribute('hidden','');
    document.getElementById("from").value='';
    document.getElementById("to").value='';
    document.getElementById("select_content").setAttribute('hidden','');
    document.getElementById("total_calculated").setAttribute('value',"");
    document.getElementById("total_modified_aux").setAttribute('value',"");
    document.getElementById("total_calculated_label").innerHTML = "0.00";
    document.getElementById("total_modified_label").innerHTML = "0.00";
  }

  function showFieldsCharges(option){
    //reset();
    document.getElementById("from").value='';
    document.getElementById("to").value='';
    document.getElementById("select_content").setAttribute('hidden','');

    document.getElementById("address_id").value = option.options[option.selectedIndex].getAttribute("data-address_id");
    resetAmounts();
  }

  function calculateF(fieldID){

  // fieldID is id set on select field
    calc = 0;
    s4f = 0;

    // get the select element
    var elements = document.getElementById(fieldID).childNodes;

    // if we want to use key=>value of selected element we will set this object
    var selectedKeyValue = {};

    // if we want to use only array of selected values we will set this array
    var arrayOfSelecedIDs=[];

    // loop over option values
    for(i=0;i<elements.length;i++){

      // if option is select then push it to object or array
      if(elements[i].selected){
        //push it to object as selected key->value
        selectedKeyValue[elements[i].value]=elements[i].textContent;
        //push to array of selected values
        arrayOfSelecedIDs.push(elements[i].value)

        calc += parseFloat(elements[i].getAttribute('data-calc'));
        s4f += parseFloat(elements[i].getAttribute('data-s4f'));

      }

    }

    // output or do seomething else with these values :)
    // check your console log
    //console.log(selectedKeyValue);
    //console.log(arrayOfSelecedIDs);

    document.getElementById("total_calculated").setAttribute('value',calc.toFixed(2));
    document.getElementById("total_modified_aux").setAttribute('value',s4f.toFixed(2));

    document.getElementById("total_calculated_label").innerHTML = calc.toFixed(2);
    document.getElementById("total_modified_label").innerHTML = s4f.toFixed(2);

    totalDiscountF();

  }

  function saveF(){
    document.getElementById("discount").setAttribute('required','');
    document.getElementById("payment_client").setAttribute('required','');
    document.getElementById("form").setAttribute('action',store);

    if (document.getElementById("form").checkValidity()) {
      var sum = 0;
      if(document.getElementById("type").value == "flat_rate_invoice"){
        //console.log("Valida Flat Rate");
        $("#machine_flat_rate :selected").map(function(i, el) {
          sum += 1;      
        }).get();
      }else{
        //console.log("Valida Charges");
        $("#charge_machine :selected").map(function(i, el) {
          sum += 1;      
        }).get();        
      }
      if(sum > 0)
        document.getElementById("form").submit();
      else
        toastr.error('Complete the fields.', '', {timeOut: 3000});
    }else
      toastr.error('Complete the fields.', '', {timeOut: 3000});
  }

  function searchF(){
    if(document.getElementById("type").value != "" && document.getElementById("client").value != "" /*&& document.getElementById("from").value != "" && document.getElementById("to").value != ""*/)
    {

      document.getElementById("discount").removeAttribute('required');
      document.getElementById("payment_client").removeAttribute('required');
      document.getElementById("form").setAttribute('action',search);
      document.getElementById("input").setAttribute('hidden','');


      var dataString = $('#'+form_id).serialize();
      $.ajax({
        dataType: 'json',
        type:'POST',
        url: search,
        cache: false,
        data: dataString,
        success: function(){
          //toastr.success('Información actualizada correctamente.', '', {timeOut: 3000});
          $("#"+div_refresh).load(" #"+div_refresh);
        },
        error: function(){
          toastr.error('There was a problem, please try again later.', '', {timeOut: 3000});
        }
      });


      setTimeout(() => {
        document.getElementById("select_content").removeAttribute('hidden');
        $('.selectpicker').selectpicker('refresh');

        document.getElementById("input").removeAttribute('hidden');
      }, 1000);

    }
  }

</script>
