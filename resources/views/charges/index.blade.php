@extends('layouts.layout')

@section('content')

<?php
  $menu= DB::select("select m.actions from menu_roles m, lookups l where m.lkp_role_id=".Auth::user()->role->id." and m.lkp_menu_id = l.id and l.key_value='Charges';");
?>

 <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="card" id="card-section">
                <div class="input-group mb-2">
                    <a href="{{action('ChargesController@create')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
                </div>

                <form method="GET" action="{{action('ChargesController@index')}}">
                    <div class="input-group mb-5">
                          <div style="width: 30%">

                          <input type="hidden" class="form-control @error('client') is-invalid @enderror input100" name="client" id="client" value="{{old('client')}}">

                          <select class="form-control selectpicker" data-live-search="true" multiple="multiple" name="clients_ids[]" id="clients_ids" title="SELECT CLIENT - BUSINESS"  onChange="getSelectedOptions(this)">
                          <option value="">ALL</option>
                          @foreach($clients as $tp)
                            <option value="{{$tp->id}}"  {{ isset($_GET['client']) ? $_GET['client'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->name}}</option>
                          @endforeach
                          </select>
                        </div>

                        <div style="margin-top: -26px; margin-left: 3px;">
                          <label > Initial:
	                        <input style="width: 200px" type="date" class="form-control @error('date_ini') is-invalid @enderror input100" name="date_ini" value="{{ isset($_GET['date_ini']) ? $_GET['date_ini'] : '' }}">
                          </label>
                        </div>

                        <div style="margin-top: -26px; margin-left: 3px;">
                          <label > Final:
                        	<input style="width: 200px" type="date" class="form-control @error('date_finnal') is-invalid @enderror input100" name="date_fin" value="{{ isset($_GET['date_fin']) ? $_GET['date_fin'] : '' }}">
                        </div>

                        	<select class="form-control" name="band_paid_out">
	                            <option value="2" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 2 ? 'selected' : '' : ''}}>ALL</option>
	                            <option value="1" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 1 ? 'selected' : '' : ''}}>PAID</option>
	                            <option value="0" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 0 ? 'selected' : '' : ''}}>WITHOUT PAYING</option>
	                        </select>
                        <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>
                
                @foreach($res as $r)
                <div style="border: 1px solid gray">
                  <div onclick="disabledDiv('{{$r->date_charge}}')" style="padding: 5px;">
                    <p style="float: left; width: 95%; padding: 5px">{{$r->date_charge}}</p>
                    <a href="#" align="right" class="btn btn-success" style="width:40px; height: 35px;"><i class="fas fa-arrow-down"></i></a>
                  </div>
                  <div style="margin-top: -100px !important;" id="{{$r->date_charge}}" hidden="">
                    <!-- Charges -->
                    @if(count($r->charges)>0)                        
                    <div class="table-responsive table-striped table-bordered" style="background: blue;">
                      <table id="table" class="table tablesorter" style="width: 96%; table-layout: fixed;font-size:14px;margin-left: 5px">
                          <tr>
                            <th style="width:30%; text-align: center;">Machine<i class="fa fa-sort"></i></th>
                            <th class="not-sortable" style="width:30%; text-align: center;">Client - Business</th>
                            
                            <th class="not-sortable" style="width:30%; text-align: center;">User Registered
                            </th>                                
                            <th class="not-sortable" style="width:30%; text-align: center;">Utility Calculated</th>
                            <th class="not-sortable" style="width:30%; text-align: center;">Utility S4F</th>
                            <th class="not-sortable" style="width:30%; text-align: center;">Payment Client</th>                       <th class="not-sortable" style="width:10%; text-align: center;"></th>
                          </tr>
                          @foreach($r->charges as $charge)
                          <tr height="20px">
                             <td style="background-color: {{$charge->row_color}}">{{$charge->id}} - {{$charge->name_machine}}</td>
                             <td style="background-color: {{$charge->row_color}}">{{$charge->client_business}}</td>                             
                             <td style="background-color: {{$charge->row_color}}">{{$charge->user_add}}</td>                             
                             <td style="background-color: {{$charge->row_color}}">{{$charge->utility_calc}}</td>
                             <td style="background-color: {{$charge->row_color}}">{{$charge->utility_s4f}}</td>
                             <td style="background-color: {{$charge->row_color}}">{{$charge->payment_client}}</td>
                             <td style="background-color:{{$charge->row_color}}">
                              <div class="col-4 active" style="padding: 0;">
                                 <a href="{{action('ChargesController@edit',$charge->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'U') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                              </div>                                
                             </td>
                          </tr>
                          @endforeach
                      </table>
                    </div>
                    @endif
                  </div>                
                </div>  
                @endforeach              
            </div>
            </div>
        </div>
    </div>
<script>
  function disabledDiv(id){
    var control = document.getElementById(id);
    if(control.hidden)
      control.hidden = false;
    else
      control.hidden = true;
  }

  function getSelectedOptions(sel) {
      var opts = [],opt;
      var len = sel.options.length;
      var ids = document.getElementById("client");
      for (var i = 1; i < len; i++) {
        opt = sel.options[i];
        if (opt.selected)
            opts.push(opt.value);
      }//for
      ids.value = opts.toString();   
    }

    function fillClients(ids){
        var arr = ids.split(",");
        $.each(arr, function(i,e){
            $("#clients_ids option[value='" + e + "']").prop("selected", true);
        });
        $("#clients_ids").selectpicker("refresh");
        document.getElementById("client").value = ids;
    }

    window.onload = function() {
        @if (isset($_GET['client']))
            fillClients("{{$_GET['client']}}");
        @endif      
    };
</script>
@stop

