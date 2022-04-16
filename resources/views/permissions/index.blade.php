@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="card" id="card-section">

                <div class="input-group mb-2">
                    <div style="width: 50%;height: 40px;">
                        <a href="{{action('PermissionController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;float: left;"><i class="fas fa-plus"></i></a>
                        <p style="margin-left: 15px;padding-top: 5px;font-weight: bold;">Assign to Machine</p>
                    </div>
                    <div align="left" style="width: 50%;height: 40px;">
                        <a href="{{action('PermissionController@createByRank')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;float: left;"><i class="fas fa-plus"></i></a>
                        <p  style="margin-left: 15px;padding-top: 5px;font-weight: bold;">By Rank</p>
                    </div>
                </div>

                <form method="GET" action="{{action('PermissionController@index')}}">
                    <div style="margin-top: 10px" class="input-group mb-5">
                        <select id="permit_type_index" class="form-control" name="type">
                            <option value="">-- Select Type --</option>
                              @foreach($types as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['type']) ? $_GET['type'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                              @endforeach
                        </select>

                        <select class="form-control selectpicker" id="machine_index" name="machine" data-live-search="true">
                            <option value="" >-- Machine --</option>
                            <option value="-1">NOT ASSIGNED (SIN ASIGNAR)</option>
                            @foreach($machines as $machine)
                                <option value="{{$machine->id}}" {{ isset($_GET['machine']) ? $_GET['machine'] == $machine->id ? 'selected' : '' : ''}}>{{$machine->id}} - {{$machine->owner}} - {{$machine->game}} - {{$machine->serial}}</option>
                            @endforeach
                        </select>

                        <input class="form-control" name="year" autofocus placeholder="Permit Year" value="{{ isset($_GET['year']) ? $_GET['year'] : '' }}">

                        <input class="form-control" name="number" autofocus placeholder="Permit Number" value="{{ isset($_GET['number']) ? $_GET['number'] : '' }}">

                        <button type="submit" class="btn btn-default" name="option"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div style="padding-bottom: 10px">
                    <p><span style="font-weight: bold">Total:</span> {{$total}} <span style="font-weight: bold;margin-left: 10px;">Assigned:</span> {{$assigned}} <span style="font-weight: bold;margin-left: 10px">Not Assigned:</span> {{$not_assigned}}
                    </p>
                </div>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table tablesorter" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                        	<th>Type Permit <i class="fa fa-sort"></i></th>
                        	<th>Machine <i class="fa fa-sort"></i></th>
                            <th>Permit Number <i class="fa fa-sort"></i></th>
                            <th>Year Permit  <i class="fa fa-sort"></th>
                            <th style="width:125px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody id="body">
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->type}}</td>
                            <td>{{$r->game}} </td>
                            <td>{{$r->permit_number}}</td>
                            <td>{{$r->year_permit}}</td>
                            <td>
                              <div class="row" style="margin-right: 0; margin-left: 0;">
                                <div class="col-4 active" style="padding: 0;">
                                  <a href="{{action('PermissionController@edit',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                                </div>
                                <div class="col-4 active" style="padding: 0;">
                                  <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('PermissionController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
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
    //$(document).ready(function() {
    window.onload = function() {
        @if(isset($_GET['machine']))
            @if($_GET['machine'])
                var machine_id = {!!$_GET['machine']!!};
                if(machine_id == -1){
                    var arr = [-1];
                    $.each(arr, function(i,e){
                        $("#machine_index option[value='" + e + "']").prop("selected", true);
                    });
                    $("#machine_index").selectpicker("refresh");
                }
            @endif
        @endif
    };

</script>
@stop

