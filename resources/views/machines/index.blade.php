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
                        <select class="form-control selectpicker" name="game" data-live-search="true" title="-- Select Game --">
                            <option value=""></option>
                              @foreach($games as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['game']) ? $_GET['game'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->name}}</option>
                              @endforeach
                        </select>

                        <select class="form-control" name="owner">
                            <option value="">-- Select Type --</option>
                              @foreach($owners as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['owner']) ? $_GET['owner'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                              @endforeach
                        </select>                        

                        <select class="form-control" name="status">
                            <option value="">-- Select Status --</option>
                              @foreach($status as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['status']) ? $_GET['status'] == $tp->id ? 'selected' : '' : '' }}>{{$tp->value}}</option>
                              @endforeach
                        </select>

                        <select class="form-control selectpicker" name="brand" data-live-search="true" title="-- Select Brand --">
                            <option value=""></option>
                              @foreach($brands as $tp)
                                <option value="{{$tp->id}}"  {{isset($_GET['brand']) ? $_GET['brand'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->brand}} {{$tp->model}} {{$tp->weight}}</option>
                              @endforeach
                        </select>

                        <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                            <th style="width:100px; text-align: center;">ID</th>
                            <th style="width:100px; text-align: center;">Owner Type</th>
                            <th style="width:100px; text-align: center;">Brand-Model</th>
                            <th style="width:100px; text-align: center;">Game Title</th>
                        	<th style="width:100px; text-align: center;">Serial</th>
                            <th style="width:100px; text-align: center;">Client</th>
                            <th style="width:150px; text-align: center;">Business</th>
                            <th style="width:150px; text-align: center;">Status</th>
                            <th style="width:125px; text-align: center;"></th>
                          <!--<th>Active</th>-->
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>                            
                            <td>{{$r->id}}</td>
                            @if($r->owner == null)
                                <td></td>
                            @else
                                <td>{{$r->owner->value}}</td>
                            @endif
                            @if($r->brand == null)
                                <td></td>
                            @else
                                <td>{{$r->brand->brand}} {{$r->brand->model}}</td>
                            @endif
                            @if($r->game == null)
                                <td></td>
                            @else
                                <td>{{$r->game->name}}</td>
                            @endif                            
                            <td>{{$r->serial}}</td>
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
