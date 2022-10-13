@extends('layouts.layout')

@section('content')

<?php
  $machineArray = array();
  foreach ($data as $key => $d)
    $machineArray[$d['machine_id']]=$d['machine_id'];

  $user_role = Auth::user()->role->key_value;
?>


  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">


          <div class="row" id="typeselect">
            <div class="">
              <div class="col-12">
                <select class="form-control" name="type" style="width: 100%;" onchange="dataInpu(this)" id="fi">
                  <option value="" selected disabled>SELECT TYPE</option>
                  @foreach($types as $type)
                    <option value="{{$type->key_value}}">{{$type->value}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12" hidden="" id="div_cli">
                <select class="form-control" id="select_cli" name="type" style="width: 100%;" onchange="loadMachines(this.selectedIndex, {{@json_encode($clients)}})">
                  <option value="" selected disabled>SELECT CLIENT-BUSINESS</option>
                  @foreach($clients as $client)
                    <option value="{{$client->id}}">{{$client->name}} - {{$client->business_name}}</option>
                  @endforeach
                </select>
              </div>
              <!--<div class="col-12" hidden="" id="div_mach">
                <select class="form-control" onchange="disabledForms()" name="type" style="width: 100%;" id="charges_machines">
                </select>-->
              </div>
            </div>
          </div>


          <div style="margin-top: 10px" class="row" id="machineselect" hidden>
            <div class="col-4">
              <select class="form-control" name="" id="charge_machine" onchange="dataCharge(this)">
                <option value="0" selected disabled>SELECT MACHINE</option>

                @foreach($machines as $machine)
                  @if(!array_key_exists($machine->id, $machineArray))
                  <option
                  value="{{$machine->id}} - {{$machine->serial}} - {{$machine->game}}"
                  class="{{$machine->master_in == "" ? 'initial' : 'charge' }}" value=""
                  data-id="{{$machine->id}}"
                  data-masterin="{{$machine->master_in}}"
                  data-masterout="{{$machine->master_out}}"
                  data-jackpotout="{{$machine->jackpot_out}}"
                  data-average="{{$machine->average}}"
                  data-band="{{$machine->band_jackpot}}"
                  data-percentage="{{$machine->percentage}}"
                  >
                  {{$machine->id}} - {{$machine->serial}} - {{$machine->game}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>

          <br>

          <div class="" id="initial-form" hidden>

            <form class="" action="{{action('ChargesController@storeInitialNumbers')}}" method="post" id="initialform">
              @csrf
              <div class="" hidden>
                <input class="form-control" type="text" id="type2" name="type" value="">
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

                <hr>
                <div class="form-group">
                  <button type="submit" name="button" class="btn btn-success">SAVE</button>
                </div>
              </div>
            </form>
          </div>

          <div id="formInputs" hidden>

            <form class="" action="{{action('ChargesController@storeData')}}" method="post" id="chargeform">
            @csrf

              <div class="form-group" hidden>
                <input class="form-control" type="text" name="machine_id" value="" id="machineid">
                <input class="form-control" type="text" name="average" value="" id="average">
                <input class="form-control" type="text" name="type" value="" id="type">
                <input class="form-control" type="text" value="" name="masterIn1" id="masterin1">
                <input class="form-control" type="text" value="" name="masterIn1" id="masterout1">
                <input class="form-control" type="text" value="" name="jackpotout1" id="jackpotout1">
                <input class="form-control" type="text" value="" name="percentage" id="percentage">
                <input class="form-control" type="text" value="" name="name" id="name">
              </div>


              <div class="card">
                <div id="avr">
                  @if($user_role == 'administrator')
                    <div class="row">
                      <div class="col-10">
                        <label id="info_numbers"></label>
                      </div>
                    </div>
                  @endif

                  <h4>Master Numbers:</h4>
                  <div class="row">
                    <div class="col-4">
                      <label for="">In <span style="color:red">*</span></label>
                      <input class="form-control" type="number" value="" name="masterIn" id="masterin" required onchange="calculate()">
                    </div>
                    <div class="col-4">
                      <label for="">Out <span style="color:red">*</span></label>
                      <input class="form-control" type="number" value="" name="masterOut" id="masterout" required onchange="calculate()">
                    </div>
                    <div class="col-4">
                      <!-- if jackpot -->
                      <div class="" hidden id="jackpot">
                        <label for="">Jackpot Out <span style="color:red">*</span></label>
                        <input class="form-control" type="number" value="" name="jackpotout" id="jackpotout" onchange="calculate()">
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
                    <label for="">Calculated</label>
                    <input class="form-control" type="number" value="" name="utility_calc" id="uc" readonly step="any">
                  </div>
                  @if($user_role == 'administrator')
                    <div class="col-4">
                  @else
                     <div class="col-4" hidden="">
                  @endif
                    <label for="">S4F</label>
                    <input class="form-control" type="number" min="" max="" value="" name="utility_s4f" id="us" step="any">
                  </div>
                  
                  <div class="col-4" style="margin-top: 30px">
                    <button type="submit" name="button" class="btn btn-info">+</button>
                  </div>
                </div>
                <hr>
              </div>
            </form>
          </div>
        </div>

        @if($data)

        <div class="table-responsive table-striped table-bordered">
        <table id="table" class="table tablesorter" style="width: 100%; table-layout: fixed;font-size:16px;">
              <thead>
                <tr style="text-align: center;">
                  <th>Machine</th>
                  <th>Utility Calculated</th>
                  <th>Utility S4F</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $key => $dt)
                  <tr>
                    <td>{{$dt['name']}}</td>
                    <td>{{$dt['utility_calc']}}</td>
                    <td>{{$dt['utility_s4f']}}</td>
                    <td> <a href="{{action('ChargesController@deleteData',$key)}}"><i class="far fa-trash-alt"></i></a> </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <hr>
          <form class="" action="{{action('ChargesController@store')}}" method="post">
            @csrf

            <?php

            $max = 0;
              $max2 = 0;
              foreach ($data as $key => $dt) {
                $max += $dt['utility_s4f'];
                $max2 += $dt['utility_calc'];
              }

            ?>

            <div class="row">
              <div class="col-4">
                <label for="">Total Calculated</label>
                <input class="form-control" type="number" value="{{$max}}" name="utility"  readonly >
              </div>
              @if($user_role == 'administrator')
                <div class="col-4">
                  <label for="">Total S4F</label>
                  <input class="form-control" type="number" value="{{$max2}}" name="s4futility"  readonly>
                </div>
              @endif
              <div class="col-4">
                <div class="row">
                  <div class="col-6">
                    <label for="">Payment Client</label>
                    <input type="number" min="0" max="{{$max}}" name="total" value="0" class="form-control" id='total' step="any">
                  </div>
                  <div class="col-6"><br>
                      <button type="submit" name="button" class="btn btn-success" style="position: absolute; bottom: 0;">SAVE</button>

                  </div>
                </div>

              </div>
            </div>


          </form>
          @endif
        </div>
      </div>
    </div>
  </div>

@stop

<script>
  function loadMachines(index,clients){
    //console.log(clients);
    //document.getElementById("machineselect").hidden = false;
    var type = document.getElementById("fi").value;
    //console.log(type);
    var machines = clients[index-1].machines;
    //console.log(machines);

    $('#charge_machine').empty();
    $('#charge_machine').append('<option value="" selected disabled>SELECT MACHINE</option>');
    for(var i=0; i < machines.length; i++){
      var band = false;
      if(type == 'initial_numbers'){
        if(machines[i].master_in == null)
          band = true;
      }else{
        if(machines[i].master_in != null)
          band = true;
      }
      if(band)
        $('#charge_machine').append('<option value="'+machines[i].id+'">'+machines[i].id+' - '+machines[i].serial+' - '+machines[i].game+'</option>');
    }
  }

  

  /*function disabledForms(){
    var type = document.getElementById("fi").value;
    document.getElementById("initial-form").hidden = true;
    document.getElementById("formInputs").hidden = true;
    if(type == 'initial_numbers')
      document.getElementById("initial-form").hidden = false;
    else{
      if(type  == 'normal_charge')
        document.getElementById("formInputs").hidden = false;
        
    }
  }*/
</script>