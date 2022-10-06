@extends('layouts.layout')

@section('content')

<?php
  $menu= DB::select("select m.actions from menu_roles m, lookups l where m.lkp_role_id=".Auth::user()->role->id." and m.lkp_menu_id = l.id and l.key_value='Machine';");
?>

 <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="card" id="card-section">

                <div class="input-group mb-2">

                    <a href="{{action('ChargesController@create')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
                </div>

                <form method="GET" action="{{action('ChargesController@index')}}">
                	
	                        <label for="">Initial Date:</label>	                        
                        	<label style="margin-left:230px;" for="">Final Date:</label>            
                    <div class="input-group mb-5">
	                        <input style="width: 50px" type="date" class="form-control @error('date_ini') is-invalid @enderror input100" name="date_ini" value="{{ isset($_GET['date_ini']) ? $_GET['date_ini'] : '' }}">
                        	<input style="width: 100px" type="date" class="form-control @error('date_finnal') is-invalid @enderror input100" name="date_fin" value="{{ isset($_GET['date_fin']) ? $_GET['date_fin'] : '' }}">
                        	<select class="form-control" name="band_paid_out">
	                            <option value="all" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == null ? 'selected' : '' : ''}}>ALL TYPES</option>
	                            <option value="1" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 1 ? 'selected' : '' : ''}}>PAID</option>
	                            <option value="0" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 0 ? 'selected' : '' : ''}}>WITHOUT PAYING</option>
	                        </select>
                        <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>


                <div class="table-responsive table-striped table-bordered">
                <table id="table" class="table tablesorter" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                          <th style="width:80px; text-align: center;">Machine ID <i class="fa fa-sort"></i></th>
                          <th class="not-sortable" style="width:200px; text-align: center;">Serial - Game Title</th>
                          <th class="not-sortable" style="width:100px; text-align: center;">Client</th>
                          <th class="not-sortable" style="width:100px; text-align: center;">Date</th>
                          <th class="not-sortable" style="width:100px; text-align: center;">Utility Calculated</th>
                          <th class="not-sortable" style="width:100px; text-align: center;">Utility S4F</th>
                          <th class="not-sortable" style="width:100px; text-align: center;">Payment Client</th>
                           <th class="not-sortable" style="width:100px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                    	<tr>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">{{$r->machine_id}}</td>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">{{$r->name_machine}}</td>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">{{$r->client_name}}</td>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">{{$r->date}}</td>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">{{$r->utility_calc}}</td>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">{{$r->utility_s4f}}</td>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">{{$r->payment_client}}</td>
                            <td style="background-color: {{$r->band_paid_out == 1 ? '#B1FEAB' :'#FEB4AB'}}">
                                <!--<div class="row" style="margin-right: 0; margin-left: 0;">
                                  <div class="col-4" style="padding: 0;">
                                    <a href="{{action('MachineController@show',$r->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'R') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                                  </div>
                                  <div class="col-4" style="padding: 0;">
                                   <a href="#" class="btn btn-link qr {{str_contains($menu[0]->actions,'R') ? '' : 'disabled' }}" style="width:40px; margin: 0" data-toggle="modal" data-action="slots4funtx/{{$r->id}}" data-id="ID: {{$r->id}}" data-target="#modalqr"><i class="fas fa-qrcode"></i></a>
                                 </div>
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <a href="{{action('MachineController@edit',$r->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'U') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                                  </div>
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('MachineController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                                  </div>

                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }} class="col-8 inactive" style="padding: 0;">
                                    <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="0" data-table="#table" data-message1="Are you sure to activate this machine?" data-message2="Activated" data-message3="Activated machine." data-method="DELETE" data-action="{{action('MachineController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
                                  </div>
                                </div>-->
                            </td>
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