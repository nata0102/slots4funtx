@extends('layouts.layout')

@section('content')

<?php
  $machineArray = array();
  foreach ($data as $key => $d)
    $machineArray[$d['machine_id']]=$d['machine_id'];

  $user_role = Auth::user()->role->key_value;
?>


  <div class="main-content" >
    <div class="section__content section__content--p30">
      <div class="container-fluid">

        <div class="card" id="card-section">
          <div id="typeselect">
            <div class="row">
                <div class="col-12 col-sm-4">
                  <select class="form-control selectpicker" {{$invoiceSelect != "" ? 'disabled' : '' }} id="select_invoice" style="width: 100%;" data-live-search="true" onchange="hiddenFields(this)">
                    <option value="" selected disabled>-- Invoince/No Invoice --</option>
                      <option value="with_invoice" {{$invoiceSelect == 'with_invoice' ? 'selected' : ''}}>WITH INVOICE</option>
                      <option value="no_invoice" {{$invoiceSelect == 'no_invoice' ? 'selected' : ''}}>NO INVOICE</option>
                  </select>
                </div>
                <div class="col-12 col-sm-4">
                  <select class="form-control selectpicker" name="type" style="width: 100%;" onchange="dataInpu(this)" id="fi" data-live-search="true">
                    <option value="" selected disabled>-- Select Type --</option>
                    @foreach($types as $type)
                      <option value="{{$type->key_value}}">{{$type->value}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-sm-4" hidden="" id="div_cli">
                  <select class="form-control selectpicker" {{$client_id != "" ? 'disabled' : '' }} id="select_cli" name="type" style="width: 100%;" onchange="loadMachines(this.selectedIndex, {{@json_encode($clients)}})" data-live-search="true">
                    <option value="" selected disabled>-- Select Client-Business --</option>
                    @foreach($clients as $client)
                      <option value="{{$client->id}}" {{$client->id == $client_id ? 'selected' : ''}}>{{$client->name}} - {{$client->business_name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-12 col-sm-4 mx-auto" id="machineselect" hidden style="margin-top: 1rem;">
                      <select class="form-control selectpicker" name="" id="charge_machine" onchange="dataCharge(this)" data-live-search="true" title="-- Select Machine --">
                      </select>
                </div>
            </div>
          </div>

          <br>

          <div class="" id="initial-form" hidden>
            <form class="" action="{{action('ChargesController@storeInitialNumbers')}}" method="post" id="initialform">
              @csrf

              <input class="form-control" type="hidden" name="invoice_select" value="" id="invoiceSelect1">
              <div class="" hidden>
                <input class="form-control" type="text" id="type2" name="type" value="">
                <input class="form-control" type="text" name="invoice_select" value="" id="invoiceSelect1">
                <input class="form-control" type="text" name="machine_id" value="" id="machineidinitial">

              </div>
              <div class="card">
                <h4>Master Numbers:</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">In <span style="color:red">*</span></label>
                    <input class="form-control" type="number" value="" name="master_in" required>
                  </div>
                  <div class="col-4">
                    <label for="">Out <span style="color:red">*</span></label>
                    <input class="form-control" type="number" value="" name="master_out" required>
                  </div>
                  <div class="col-4">
                    <!-- if jackpot -->
                    <div class="" hidden id="jackpotinitial">
                      <label for="">Jackpot Out</label>
                      <input class="form-control" type="number" value="" name="jackpot_out" id="jpinitial">
                    </div>
                  </div>
                </div>

                <h4>Period Numbers:</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">In</label>
                    <input class="form-control" type="number" value="" name="period_in">
                  </div>
                  <div class="col-4">
                    <label for="">Out</label>
                    <input class="form-control" type="number" value="" name="period_out">
                  </div>
                  <div class="col-4">
                    <label for="">Date</label>
                    <input class="form-control" type="date" name="period_date" value="">
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" name="button" class="btn btn-success">SAVE</button>
                </div>
              </div>
            </form>
          </div>

          <div id="formInputs" hidden>

            <form class="" action="{{action('ChargesController@storeData')}}" method="post" id="chargeform">
              @csrf

              <input type="hidden" name="client" value="" id="input_client">
              <input class="form-control" type="hidden" name="invoice_select" value="" id="invoiceSelect2">
              <div class="form-group" hidden>
                <input class="form-control" type="text" name="machine_id" value="" id="machineid">
                <input class="form-control" type="text" name="average" value="" id="average">
                <input class="form-control" type="text" name="type" value="" id="type">
                <input class="form-control" type="text" value="" name="masterIn1" id="masterin1">
                <input class="form-control" type="text" value="" name="masterIn1" id="masterout1">
                <input class="form-control" type="text" value="" name="jackpotout1" id="jackpotout1">
                <input class="form-control" type="text" value="" name="percentage" id="percentage">
                <input class="form-control" type="text" value="" name="name" id="name">
                <input class="form-control" type="text" name="machine_name" value="" id="machinename">

              </div>


              <div class="card">
                <div id="avr">
                  <h4>Master Numbers:</h4>
                  <div class="row">
                    <div class="col-4">
                      <label for="">In  @if($user_role == 'administrator')<span id="ant_in"></span> @endif<span style="color:red">*</span></label>
                      <input class="form-control" type="number" value="" name="masterIn" id="masterin" required onchange="calculate()" onkeyup="this.onchange();">
                    </div>
                    <div class="col-4">
                      <label for="">Out @if($user_role == 'administrator')<span id="ant_out"></span> @endif<span style="color:red">*</span></label>
                      <input class="form-control" type="number" value="" name="masterOut" id="masterout" required onchange="calculate()" onkeyup="this.onchange();">
                    </div>
                    <div class="col-4">
                      <!-- if jackpot -->
                      <div class="" hidden id="jackpot">
                        <label for="">Jackpot Out @if($user_role == 'administrator')<span id="ant_jackpot"></span> @endif<span style="color:red">*</span></label>
                        <input class="form-control" type="number" value="" name="jackpotout" id="jackpotout" onchange="calculate()" onkeyup="this.onchange();">
                      </div>
                    </div>
                  </div>

                  <hr>

                  <h4>Period Numbers:</h4>
                  <div class="row">
                    <div class="col-4">
                      <label for="">In</label>
                      <input class="form-control" type="number" value="" name="periodIn">
                    </div>
                    <div class="col-4">
                      <label for="">Out</label>
                      <input class="form-control" type="number" value="" name="periodOut">
                    </div>
                    <div class="col-4">
                      <label for="">Date</label>
                      <input class="form-control" type="date" name="date" value="">
                    </div>
                  </div>
                  <hr>
                </div>

                <h4>Machine Utility:</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">Calculated System</label>
                    <input class="form-control" type="number" value="" name="utility_calc" id="uc" readonly step=".01">
                  </div>
                  <div class="col-4"  id="div_input_utility_s4f">
                    <label for="">S4F</label>
                    <input class="form-control" type="number" min="" max="" value="" name="utility_s4f" id="us" step=".01">
                  </div>
                  <div class="col-12" style="margin-top: 30px">
                    <button type="submit" name="button" class="btn btn-info">+</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        @if($data)

          <div style="margin-top: 20px;" class="table-responsive table-striped table-bordered">
          <table id="table" class="table tablesorter" style="width: 100%; table-layout: fixed;font-size:16px;">
              <thead>
                <tr style="text-align: center;">
                  <th>Machine</th>
                  <th>Utility System</th>
                  <th>Utility S4F</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $key => $dt)
                  <?php
                    $total = 0;
                  ?>
                  <tr>
                    <td>{{$dt['machine_name']}}</td>
                    <td>{{$dt['utility_calc']}}</td>
                    <td>{{$dt['utility_s4f']}}</td>
                    <td> <a href="{{action('ChargesController@deleteData',$key)}}"><i class="far fa-trash-alt"></i></a> </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <hr>
          <div id="cobro">


          <form class="" action="{{action('ChargesController@store')}}" method="post">

            @csrf
              <?php
                $total_system = 0;
                $total_modified = 0;
                $total_invoice = 0;
                foreach ($data as $key => $dt) {
                  $total_modified += $dt['utility_s4f'];
                  $total_system += $dt['utility_calc'];
                  $total_invoice += $dt['utility_calc'];
                }
              ?>
              <input type="hidden" name="type_invoice" id="type_invoice">
              <input type="hidden" name="total_invoice" id="total_invoice" value="{{$total_invoice}}">
              <input type="hidden" name="total_invoice_modified" id="total_invoice_modified" value="{{$total_modified}}">
              <input type="hidden" name="client_address_id" value="" id="client_address_id">

              <div class="row">
                <div class="col-4">
                  <label id="label_total" style="font-weight: bold" for="">Total System:</label>
                  <label>$</label><label id="total_calculated" value="{{$total_system}}" name="utility" value="{{$total_system}}">{{$total_system}}</label>
                </div>
                <div class="col-4">
                  <label id="label_modified" style="font-weight: bold" for="">Total S4F:</label>
                  <label>$</label><label value="{{$total_modified}}" id="total_modified">{{$total_modified}}</label>
                </div>
              </div>

              <div class="row">
                <div class="col-4" id="totals_with_invoice">
                  <label for="">Discount %</label>
                  <input class="form-control" type="number" value="0" min="0" max="100" name="discount" id="discount" onchange="totalDiscount(this)" onkeyup="this.onchange();">
                </div>
                <div class="col-4">
                  <label for="">Payment Client</label>
                  <input type="number" min="0" max="{{$total_modified}}" name="payment_client" value="0" class="form-control" id='payment_client' step="any">
                </div>
                  <div class="col-6"><br>
                      <button type="submit" name="button" class="btn btn-success" style="">SAVE</button>
                  </div>
              </div>
            </form>
          </div>


        @endif

    </div>
  </div>
  </div>
  <br>


@stop

<script>

  function hiddenFields(e){
    var user_role = {!! json_encode($user_role) !!};
    var div_input_utility_s4f = false;
    var totals_with_invoice = true;
    var label_total = "Total System", label_modified = "Total S4F";
    if(user_role != 'administrator' || e.options[e.selectedIndex].getAttribute("value") == "with_invoice"){
      div_input_utility_s4f = true;
      totals_with_invoice = false;
      label_total = "Total Invoice:";
      label_modified = "Total With Discount:";
    }

    document.getElementById("invoiceSelect1").value = e.options[e.selectedIndex].getAttribute("value");
    document.getElementById("invoiceSelect2").value = e.options[e.selectedIndex].getAttribute("value");
    if(document.getElementById("type_invoice"))
    document.getElementById("type_invoice").value = e.options[e.selectedIndex].getAttribute("value");

    if(document.getElementById("div_input_utility_s4f"))
    document.getElementById("div_input_utility_s4f").hidden=div_input_utility_s4f;
    if(document.getElementById("totals_with_invoice"))
    document.getElementById("totals_with_invoice").hidden=totals_with_invoice;
    if(document.getElementById("type_invoice"))
    document.getElementById("type_invoice").value = e.options[e.selectedIndex].getAttribute("value");
    if(document.getElementById("label_total"))
    document.getElementById("label_total").innerHTML=label_total;
    if(document.getElementById("label_modified"))
    document.getElementById("label_modified").innerHTML=label_modified;
  }

  /*function changeBandInvoice(machine_id) {

    console.log(machine_id);

    url = "{{action('ChargesController@index')}}"+"/update_data/"+ machine_id;
    console.log(url);

    $.ajax({
      dataType: 'json',
      type:'POST',
      url: url,
      cache: false,
      data: {'key' : machine_id,'_token':"{{ csrf_token() }}"},
      success: function(){
        toastr.success('Información actualizada correctamente.', '', {timeOut: 3000});
        $("#cobro").load(" #cobro");
      },
      error: function(){
        toastr.error('Hubo un problema por favor intentalo de nuevo mas tarde.', '', {timeOut: 3000});
      }
    });
  }*/





  function loadMachines(index,clients){
    client_id = clients[index-1].id;
    client_address_id = clients[index-1].address_id;
    document.getElementById("input_client").value = client_id;
    if(  document.getElementById("client_address_id"))
     document.getElementById("client_address_id").value = client_address_id;

    var type = document.getElementById("fi").value;
    if(type)

    document.getElementById("machineselect").hidden =false;
    var machines = clients[index-1].machines;

    machinesList = "{{$machinesid}}";
    machinesList = machinesList.split(",");

    $('#charge_machine').empty();
    for(var i=0; i < machines.length; i++){
      var band = false;
      if(type == 'initial_numbers'){
        if(machines[i].master_in == null)
          band = true;
      }else{
        if(machines[i].master_in != null)
          band = true;
      }
      if(band){
        x = machines[i].id.toString() ;
        if( machinesList.includes(x ) == false)
          $('#charge_machine').append('<option data-id="'+machines[i].id+'" data-masterin="'+machines[i].master_in+'" data-masterout="'+machines[i].master_out+'" data-jackpotout="'+machines[i].jackpot_out+'" data-average="'+machines[i].average+'" data-band="'+machines[i].band_jackpot+'" data-percentage="'+machines[i].percentage+'"  value="'+machines[i].id+'">'+machines[i].id+' - '+machines[i].serial+' - '+machines[i].game+'</option>');
      }
    }
    $('#charge_machine').selectpicker('refresh');
  }



</script>
