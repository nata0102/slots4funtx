@extends('layouts.layout')

@section('content')

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="" id="card-section">

        <h2>Hola "Nombre"</h2>

        <div class="row m-t-25">

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('MachineController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Machines</h4>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('PartController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Parts</h4>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('MachineBrandController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Machine Brands</h4>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('PermissionController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Permissions</h4>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('PercentagePriceController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Percentage/Amount</h4>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('LookupController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Configuration</h4>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('ClientController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Clients</h4>
                </div>
              </div>
            </a>
          </div>

        </div>


      </div>
    </div>
  </div>
</div>

@stop
