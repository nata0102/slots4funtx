@extends('layouts.layout')

@section('content')

<?php
  $menu= DB::select("select m.actions from menu_roles m, lookups l where m.lkp_role_id=".Auth::user()->role->id." and m.lkp_menu_id = l.id and l.key_value='Invoice';");
?>
  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <div class="input-group mb-2">
            <a href="{{action('InvoiceController@create')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
          </div>

          <form method="GET" action="{{action('InvoiceController@index')}}">
            <div class="input-group mb-5">   
              <select onchange="fillClients(this.value,{{json_encode($typesClients)}})" style="margin-top: 25px" class="form-control" name="type" id="type">
                  <option value="all">ALL</option>
                @foreach($typesClients as $type)
                  <option value="{{$type->key_value}}" {{ isset($_GET['type']) ? $_GET['type'] == -1 ? 'selected' : '' : ''}}>{{$type->value}}</option>
                @endforeach
              </select>  

              <input type="hidden" class="form-control @error('client') is-invalid @enderror input100" name="client" id="client" value="{{old('client')}}">
            

              <select style="margin-top: 40px" class="form-control selectpicker" data-live-search="true" multiple="multiple" name="clients_ids[]" id="clients_ids" title="SELECT CLIENTS - BUSINESS" 
              onChange="getSelectedOptions(this)">
              </select>   

              <div style="margin-top: 0px; margin-left: 3px;">
                <label > Initial:
                <input style="width: 200px" type="date" class="form-control @error('date_ini') is-invalid @enderror input100" name="date_ini" value="{{ isset($_GET['date_ini']) ? $_GET['date_ini'] : '' }}">
                </label>
              </div>

              <div style="margin-top: 0px; margin-left: 3px;">
                <label > Final:
                <input style="width: 200px" type="date" class="form-control @error('date_finnal') is-invalid @enderror input100" name="date_fin" value="{{ isset($_GET['date_fin']) ? $_GET['date_fin'] : '' }}">
              </div>

              <select style="margin-top: 25px" class="form-control" name="band_paid_out">
                <option value="-1" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == -1 ? 'selected' : '' : ''}}>ALL</option>
                <option value="2" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 2 ? 'selected' : '' : ''}}>CANCELLED INVOICES</option>
                <option value="1" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 1 ? 'selected' : '' : ''}}>PAID</option>
                <option value="0" {{ isset($_GET['band_paid_out']) ? $_GET['band_paid_out'] == 0 ? 'selected' : '' : ''}}>WITHOUT PAYING</option>
              </select>
              <button style="margin-top: 10px" type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
              </button>
            </div>
             
          </form>

          <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0;">
            <table id="table" class="table" style="width: 100%; table-layout: fixed;">
              <thead>
                <tr>
                  <th style="width:20%; text-align: center;">Folio</th>
                  <th style="width:20%; text-align: center;">Date</th>
                  <th style="width:20%; text-align: center;">Type</th>
                  <th style="width:40%; text-align: center;">Client</th>
                  <th style="width:20%; text-align: center;">Total ($)</th>
                  <th style="width:20%; text-align: center;">Debit ($)</th>
                	<th style="width:20%; text-align: center;"></th>
                </tr>
              </thead>
              <tbody>
              @foreach($res as $invoice)
                <tr>
                  <td style="text-align: center;background-color: {{$invoice->row_color}}">{{$invoice->folio}}</td>
                  <td style="text-align: center;background-color: {{$invoice->row_color}}">{{$invoice->date_invoice}}</td>
                  <td style="text-align: center;background-color: {{$invoice->row_color}}">{{$invoice->type_value}}</td>
                  <td style="text-align: center;background-color: {{$invoice->row_color}}">{{$invoice->client_name}} - {{$invoice->business_name}}</td>
                  <td style="text-align: right; background-color: {{$invoice->row_color}}">{{$invoice->total_discount}}</td>
                  <td style="text-align: right; background-color: {{$invoice->row_color}}">{{$invoice->debit}}</td>
                  <td style="background-color: {{$invoice->row_color}}">
                    <div class="row" style="margin-right: 0; margin-left: 0;">
                      <div>                        
                        <a href="{{action('InvoiceController@show',$invoice->id)}}" target="_blank" align="right" class="btn btn-link {{str_contains($menu[0]->actions,'R') ? '' : 'disabled' }}" style="width:40px; height: 35px;"><i class="fas fa-file-invoice-dollar"></i></a>
                      </div>

                      <div align="right" style="padding: 0; margin-left: 20px">
                        <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="0" data-table="#table" data-message1="Are you sure to cancel this Invoice?" data-message2="Activated" data-message3="Activated client." data-method="DELETE" data-action="{{action('InvoiceController@destroy',$invoice->id)}}" style="width:40px; margin:0; padding: 0"><i class="fas fa-times"></i></button>
                      </div>
                    </div>
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

  
  <script>
    function fillClients(value, typesClients){
      $('#clients_ids').empty();
      $('#clients_ids').append('<option value="all">ALL CLIENTS</option>');
      
      for(var i = 0; i < typesClients.length; i++){
        if(typesClients[i].key_value == value){
          for(var j = 0; j < typesClients[i].clients.length; j++ ){ 
            $('#clients_ids').append('<option value="'+typesClients[i].clients[j].id+'">'+typesClients[i].clients[j].client_name+' - '+typesClients[i].clients[j].business_name+'</option>');
          }
          break;
        }
      }
      $("#clients_ids").selectpicker("refresh");
    }

    function getSelectedOptions(sel) {
      var opts = [],opt;
      var len = sel.options.length;
      var ids = document.getElementById("client");

      for (var i = 0; i < len; i++) {
        opt = sel.options[i];
        if (opt.selected)
            opts.push(opt.value);
      }
      ids.value = opts.toString();
    }

    function selectionType(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#type option[value='" + e + "']").prop("selected", true);
      });
      $("#type").selectpicker("refresh");
    }

    function fillClientsSelected(ids){
        var arr = ids.split(",");
        $.each(arr, function(i,e){
            $("#clients_ids option[value='" + e + "']").prop("selected", true);
        });
        $("#clients_ids").selectpicker("refresh");
        document.getElementById("client").value = ids;
    }


    window.onload = function() {
     if($('#type').val() != ""){        
        @if(isset($_GET['type']))
          selectionType("{{$_GET['type']}}");
          fillClients($('#type').val(), {!!json_encode($typesClients)!!});
          @if (isset($_GET['client']))
              fillClientsSelected("{{$_GET['client']}}");
          @endif
        @endif
     }
  };
  </script>
@stop