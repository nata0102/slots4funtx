
@extends('layouts.layout')

@section('content')


  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">


          <div class="row" id="typeselect">
            <div class="">
              <div class="col-12">
                <select class="form-control" name="type" style="width: 100%;" onchange="dataInpu(this)" id="fi">
                  <option value="" selected disabled>Selecciona un tipo</option>
                  @foreach($types as $type)
                    <option value="{{$type->key_value}}">{{$type->value}}</option>

                  @endforeach
                </select>
              </div>
            </div>
          </div>


          <div class="row" id="machineselect" hidden>
            <div class="col-4">
              <label for="">Selecciona una maquina</label>
              <select class="form-control" name="" id="charge_machine" onchange="dataCharge(this)">
                <option value="0" selected disabled>Selecciona una maquina</option>
                @foreach($machines as $machine)
                  @if(!array_key_exists($machine->id, $data))
                  <option
                  value="{{$machine->id}} {{$machine->serial}} {{$machine->game}}"
                  class="{{$machine->master_in == "" ? 'initial' : 'charge' }}" value=""
                  data-id="{{$machine->id}}"
                  data-masterin="{{$machine->master_in}}"
                  data-masterout="{{$machine->master_out}}"
                  data-jackpotout="{{$machine->jackpot_out}}"
                  data-average="{{$machine->average}}"
                  data-band="{{$machine->band_jackpot}}"
                  data-percentage="{{$machine->percentage}}"
                  >
                  {{$machine->id}} {{$machine->serial}} {{$machine->game}}</option>
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

                <h4>Master numbers</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">In*</label>
                    <input class="form-control" type="number" value="" name="master_in" required>
                  </div>
                  <div class="col-4">
                    <label for="">Out*</label>
                    <input class="form-control" type="number" value="" name="master_out" required>
                  </div>
                  <div class="col-4">
                    <!-- if jackpot -->
                    <div class="" hidden id="jackpotinitial">
                      <label for="">Jackpot out</label>
                      <input class="form-control" type="number" value="" name="jackpot_out" id="jpinitial">
                    </div>
                  </div>
                </div>

                <h4>Period numbers</h4>
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
                  <button type="submit" name="button" class="btn btn-success">SEND</button>
                </div>

              </div>



            </form>

          </div>

          <div id="formInputs">

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



                  <h4>Master numbers</h4>
                  <div class="row">
                    <div class="col-4">
                      <label for="">In*</label>
                      <input class="form-control" type="number" value="" name="masterIn" id="masterin" required onchange="calculate()">
                    </div>
                    <div class="col-4">
                      <label for="">Out*</label>
                      <input class="form-control" type="number" value="" name="masterOut" id="masterout" required onchange="calculate()">
                    </div>
                    <div class="col-4">
                      <!-- if jackpot -->
                      <div class="" hidden id="jackpot">
                        <label for="">Jackpot out</label>
                        <input class="form-control" type="number" value="" name="jackpotout" id="jackpotout" onchange="calculatejp()">
                      </div>
                    </div>
                  </div>

                  <hr>

                  <h4>Period numbers</h4>
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


                <h4>Machine Utility</h4>
                <div class="row">
                  <div class="col-4">
                    <label for="">Calc. Utility</label>
                    <input class="form-control" type="number" value="" name="uc" id="uc" readonly step="any">
                  </div>
                  <div class="col-4">
                    <label for="">S4F Utility</label>
                    <input class="form-control" type="number" min="0" max="" value="" name="us" id="us" step="any">
                  </div>
                  <div class="col-4">
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
                <tr>
                  <th hidden>Machine id</th>
                  <th>Machine</th>
                  <th hidden>type</th>
                  <th hidden>average</th>
                  <th hidden>Master In</th>
                  <th hidden>Master Out</th>
                  <th hidden>Period In</th>
                  <th hidden>Period Out</th>
                  <th hidden>Date</th>
                  <th>Utlity</th>
                  <th>S4F Utlity</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $key => $dt)
                  <tr>
                    <td hidden>{{$dt['machine_id']}}</td>
                    <td>{{$dt['name']}}</td>
                    <td hidden>{{$dt['type']}}</td>
                    <td hidden>{{$dt['average']}}</td>
                    <td hidden>{{$dt['masterIn']}}</td>
                    <td hidden>{{$dt['masterOut']}}</td>
                    <td hidden>{{$dt['periodIn']}}</td>
                    <td hidden>{{$dt['periodOut']}}</td>
                    <td hidden>{{$dt['date']}}</td>
                    <td>{{$dt['uc']}}</td>
                    <td>{{$dt['us']}}</td>
                    <td> <a href="{{action('ChargesController@deleteData',$key)}}">Delete</a> </td>
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
                $max += $dt['us'];
                $max2 += $dt['uc'];
              }

            ?>

            <div class="row">
              <div class="col-4">
                <label for="">Calc. Utility</label>
                <input class="form-control" type="number" value="{{$max}}" name="max"  readonly >
              </div>
              <div class="col-4">
                <label for="">S4F Utility</label>
                <input class="form-control" type="number" value="{{$max2}}" name="max2"  readonly>
              </div>
              <div class="col-4">
                <label for="">Payment</label>

                <input type="number" min="0" max="{{$max}}" name="total" value="0" class="form-control" id='total' style="width: 120px;">
              </div>
            </div>

            <div class="form-group">
              <button type="submit" name="button" class="btn btn-success">SEND</button>
            </div>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>

@stop
