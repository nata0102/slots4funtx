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
                	<th style="width:20%; text-align: center;"></th>
                </tr>
              </thead>
              <tbody>
              @foreach($res as $invoice)
                <tr>
                  <td style="text-align: center;">{{$invoice->folio}}</td>
                  <td style="text-align: center;">{{$invoice->date_invoice}}</td>
                  <td style="text-align: center;">{{$invoice->type_value}}</td>
                  <td style="text-align: center;">{{$invoice->client_name}}</td>
                  <td style="text-align: right;">{{$invoice->total_discount}}</td>
                  <td>
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

  @stop
