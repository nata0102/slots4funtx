@extends('layouts.layout')

@section('content')


  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">


          <div class="row">
            <div class="col-4">
              <label for="">Selecciona una maquina</label>
              <select class="form-control" name="" id="charge_machine" onchange="dataCharge(this)">
                <option value="" selected disabled>Selecciona una maquina</option>
                @foreach($machines as $machine)
                  <option value=""
                  data-id="{{$machine->id}}"
                  data-masterin="{{$machine->master_in}}"
                  data-masterout="{{$machine->master_out}}"
                  data-jackpotout="{{$machine->jackpot_out}}"
                  data-average="{{$machine->average}}"
                  data-band="{{$machine->band_jackpot}}"
                  >
                  {{$machine->id}} {{$machine->serial}} {{$machine->game}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <br>

          <div class="row" id="typeselect" hidden>
            <div class="">
              <div class="col-12">
                <select class="form-control" name="type" style="width: 100%;" onchange="dataInput(this)">
                  <option value="" selected disabled>Selecciona un tipo</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
            </div>
          </div>


          <div id="formInputs" hidden>

            <form class="" action="{{action('ChargesController@store')}}" method="post">
            @csrf

              <div class="form-group" hidden>
                <label for="">Machine_id</label>
                <input class="form-control" type="text" name="machine_id" value="" id="machineid">
              </div>

              <div class="form-group" hidden>
                <label for="">Average</label>
                <input class="form-control" type="text" id="average" name="average" value="">
              </div>

              <div class="form-group" hidden>
                <label for="">Type</label>
                <input class="form-control" type="text" id="type" name="type" value="">
              </div>


              <div class="card" hidden id='initial'>
                <h4>Initial numbers</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">In*</label>
                    <input class="form-control" type="text" value="" name="masterIn1" readonly id="masterin1" onchange="calculate()">
                  </div>
                  <div class="col-4">
                    <label for="">Out*</label>
                    <input class="form-control" type="text" value="" name="masterOut1" readonly id="masterout1" onchange="calculate()">
                  </div>
                  <div class="col-4">
                    <!-- if jackpot -->
                    <div class="" hidden id="jackpot1">
                      <label for="">Jackpot out</label>
                      <input class="form-control" type="text" value="" name="jackpotout1" readonly id="jackpotout1" >
                    </div>
                  </div>
                </div>


                <h4>Master numbers</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">In*</label>
                    <input class="form-control" type="text" value="" name="masterIn" id="masterin" required onchange="calculate()">
                  </div>
                  <div class="col-4">
                    <label for="">Out*</label>
                    <input class="form-control" type="text" value="" name="masterOut" id="masterout" required onchange="calculate()">
                  </div>
                  <div class="col-4">
                    <!-- if jackpot -->
                    <div class="" hidden id="jackpot">
                      <label for="">Jackpot out</label>
                      <input class="form-control" type="text" value="" name="jackpotout" id="jackpotout">
                    </div>
                  </div>
                </div>

                <hr>

                <h4>Period numbers</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">In</label>
                    <input class="form-control" type="text" value="" name="periodIn">
                  </div>
                  <div class="col-4">
                    <label for="">Out</label>
                    <input class="form-control" type="text" value="" name="periodOut">
                  </div>
                  <div class="col-4">
                    <label for="">date</label>
                    <input class="form-control" type="date" name="date" value="">
                  </div>
                </div>

                <hr>



                <h4>Machine Utility</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">Utilidad Calc</label>
                    <input class="form-control" type="text" value="" name="granTotal" id="uc">
                  </div>
                  <div class="col-4">
                    <label for="">utilidad s4f</label>
                    <input class="form-control" type="text" value="" name="us" id="us">
                  </div>
                  <div class="col-4">
                    <label for="">pago cliente</label>
                    <input class="form-control" type="text" name="pago" value="" id="pago">
                  </div>
                </div>

              </div>
              <button type="submit" name="" class="btn btn-success">Add</button>

            </form>
          </div>



        </div>

        <div class="table-responsive table-striped table-bordered">
        <table id="table" class="table tablesorter" style="width: 100%; table-layout: fixed;font-size:16px;">
              <thead>
                <tr>
                  <th>Machine id</th>
                  <th>type</th>
                  <th>average</th>
                  <th>Master In</th>
                  <th>Master Out</th>
                  <th>Period In</th>
                  <th>Period Out</th>
                  <th>Date</th>
                  <th>Utlity</th>



                </tr>
              </thead>
              <tbody>
                @foreach($data as $dt)
                  <tr>
                    <td>{{$dt['machine_id']}}</td>
                    <td>{{$dt['type']}}</td>
                    <td>{{$dt['average']}}</td>
                    <td>{{$dt['masterIn']}}</td>
                    <td>{{$dt['masterOut']}}</td>
                    <td>{{$dt['periodIn']}}</td>
                    <td>{{$dt['periodOut']}}</td>
                    <td>{{$dt['date']}}</td>
                    <td>{{$dt['granTotal']}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

@stop
