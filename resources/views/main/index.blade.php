@extends('layouts.layout')

@section('content')
<?php
$menus = DB::select('select m.*,l.key_value,l.value from menu_roles m, lookups l where m.lkp_role_id='.Auth::user()->role->id.' and m.lkp_menu_id = l.id;');
?>
<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="" id="card-section">

        <h2>Hola</h2>



        <div class="row m-t-25">

          @foreach($menus as $menu)
          <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-3">
            <?php $action = $menu->key_value.'Controller@index' ?>
            <a href="{{action($action)}}">
              <div class="text-center div-card">
                <div class="">
                  <i class="fas fa-th-list" style="font-size: 40px;"></i>
                  <h4>{{$menu->value}}</h4>
                </div>
              </div>
            </a>
          </div>
          @endforeach
          
        </div>


      </div>
    </div>
  </div>
</div>

@stop
