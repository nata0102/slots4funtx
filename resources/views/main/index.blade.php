@extends('admin.layout')

@section('content')

<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="" id="card-section">

        <h2>Hola "Nombre"</h2>

        <div class="row m-t-25">

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="#">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Maquinas</h4>
                </div>
              </div>
            </a>
          </div>

          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <a href="{{action('PartController@index')}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>Piezas</h4>
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
