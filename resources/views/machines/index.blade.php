@extends('layouts.layout')

@section('content')

    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="card" id="card-section">

                <div class="input-group mb-2">
                    <a href="{{action('MachineController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
                </div>

                <form method="GET" action="{{action('MachineController@index')}}">
                    <div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                        <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                        <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
                    </div>
                    <div class="input-group mb-5">
                        <input class="form-control" type="text" name="game" autofocus placeholder="Game Title" value="{{ isset($_GET['game']) ? $_GET['game'] : '' }}">

                        <input class="form-control" name="owner" autofocus placeholder="Owner Type (Mine/Service)" value="{{ isset($_GET['owner']) ? $_GET['owner'] : '' }}">

                        <input class="form-control" name="status" autofocus placeholder="Status" value="{{ isset($_GET['status']) ? $_GET['status'] : '' }}">

                        <input class="form-control" name="brand" autofocus placeholder="Brand" value="{{ isset($_GET['brand']) ? $_GET['brand'] : '' }}">

                        <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                        	<th style="width:100px; text-align: center;">Game Title</th>
                        	<th style="width:100px; text-align: center;">Owner Type</th>
                        	<th style="width:100px; text-align: center;">Serial</th>
                        	<th style="width:100px; text-align: center;">Inventory</th>
                          <th style="width:100px; text-align: center;">Client</th>
                          <th style="width:150px; text-align: center;">Business</th>
                          <th style="width:150px; text-align: center;">Status</th>
                          <th style="width:100px; text-align: center;">Machine Brand</th>
                          <th style="width:175px; text-align: center;">Date Sale</th>
                          <th style="width:125px; text-align: center;"></th>
                          <!--<th>Active</th>-->
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->game_title}}</td>
                            @if($r->owner == null)
                                <td></td>
                            @else
                                <td>{{$r->owner->value}}</td>
                            @endif
                            <td>{{$r->serial}}</td>
                            <td>{{$r->inventory}}</td>
                            @if($r->address_id == null)
                                <td></td>
                                <td></td>
                            @else
                                <td>{{$r->address->client->name}}</td>
                                <td>{{$r->address->name_address}}</td>
                            @endif
                            @if($r->status == null)
                                <td></td>
                            @else
                                <td>{{$r->status->value}}</td>
                            @endif
                            @if($r->brand == null)
                                <td></td>
                            @else
                                <td>{{$r->brand->brand}} {{$r->brand->model}} {{$r->brand->weight}} lbs.</td>
                            @endif
                            <td>{{$r->date_sale}}</td>
                            <td>
                                <div class="row" style="margin-right: 0; margin-left: 0;">
                                  <div class="col-4" style="padding: 0;">
                                    <a href="{{action('MachineController@show',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                                  </div>
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <a href="{{action('MachineController@edit',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                                  </div>
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('MachineController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                                  </div>

                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }} class="col-8 inactive" style="padding: 0;">
                                    <button class="delete-alert btn btn-link" data-reload="0" data-table="#table" data-message1="Are you sure to activate this machine?" data-message2="Activated" data-message3="Activated machine." data-method="DELETE" data-action="{{action('MachineController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
                                  </div>
                                </div>
                            </td>
                            <!--@if($r->active == 1)
                                <td><div class="form-check"><input style="display:block;margin:0 auto;" class="form-check-input" type="checkbox" value="" checked disabled></div></td>
                            @else
                                <td><div class="form-check"><input style="display:block;margin:0 auto;" class="form-check-input" type="checkbox" value="" disabled></div></td>
                            @endif-->
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
