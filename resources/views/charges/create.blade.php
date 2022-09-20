
@extends('layouts.layout')

@section('content')


  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <div class="row">
            <div class="col-6">
              <label for="">Selecciona una maquina</label>
              <select class="" name="">
                <option value="">Selecciona una maquina</option>
                @foreach($machines as $machine)
                  <option value="">{{$machine->serial}}</option>
                @endforeach
              </select>
            </div>
          </div>


          <div class="card">
            <h4>Master numbers</h4>
            <div class="row">
              <div class="col-4">
                <label for="">In</label>
                <input class="form-group" type="text" value="" name="masterIn" readonly>
              </div>
              <div class="col-4">
                <label for="">Out</label>
                <input class="form-group" type="text" value="" name="masterOut" readonly>
              </div>
              <div class="col-4">
                <!-- if jackpot -->
                <div class="">
                  <label for="">Jackpot out</label>
                  <input class="form-group" type="text" value="" name="granTotal" readonly>
                </div>
              </div>
            </div>

            <h4>Period numbers</h4>
            <div class="row">
              <div class="col-4">
                <label for="">In</label>
                <input class="form-group" type="text" value="" name="periodIn" readonly>
              </div>
              <div class="col-4">
                <label for="">Out</label>
                <input class="form-group" type="text" value="" name="periodOut" readonly>
              </div>
              <div class="col-4">
                <label for="">date</label>
                <input type="date" name="date" value="">
              </div>
            </div>

            <div class="row">
              <div class="col-6">

              </div>
              <div class="col-6">

              </div>
              <div class="col-6">
                <label for="">Total machine</label>
                <input class="form-group" type="text" value="" name="granTotal" readonly>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>

@stop
