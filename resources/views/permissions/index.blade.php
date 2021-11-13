@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="card" id="card-section">

                <div class="input-group mb-2">
<<<<<<< HEAD
                    <a href="{{action('PermissionController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
=======
                    <a href="{{action('PermissionController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a> 
                    <p style="margin-left: 10px;padding-top: 5px;font-weight: bold;">Assign to Machine</p>  
>>>>>>> c99c1f1be9f0f10f09a65bda4c1e0945d6ccc293
                    <a href="{{action('PermissionController@createByRank')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;margin-left: 250px;"><i class="fas fa-plus"></i>
                    </a>
                    <p style="margin-left: 10px;padding-top: 5px;font-weight: bold;">By Rank</p>
                </div>

                <form method="GET" action="{{action('PermissionController@index')}}">
                    <div class="input-group mb-5">
                        <select class="form-control" name="type">
                            <option value="">-- Select Type --</option>
                              @foreach($types as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['type']) ? $_GET['type'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                              @endforeach
                        </select>

                        <input class="form-control" name="machine" autofocus placeholder="Machine" value="{{ isset($_GET['machine']) ? $_GET['machine'] : '' }}">

                        <input class="form-control" name="number" autofocus placeholder="Permit Number" value="{{ isset($_GET['number']) ? $_GET['number'] : '' }}">

                        <button type="submit" class="btn btn-default" name="option"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                        	<th>Type Permit</th>
                        	<th>Machine</th>
                            <th>Permit Number</th>
                            <th>Year Permit</th>
                            <th style="width:125px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->type}}</td>
                            <td>{{$r->game}}</td>
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
@stop
