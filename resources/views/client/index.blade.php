@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <div class="input-group mb-2">
            <a href="{{action('ClientController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
          </div>

          <form method="GET" action="{{action('ClientController@index')}}">
              <div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
              </div>
              <div class="input-group mb-5">
                  <input class="form-control" type="text" name="name" value="{{ isset($_GET['name']) ? $_GET['name'] : '' }}" placeholder="Name">
                  <input class="form-control" type="text" name="phone" value="{{ isset($_GET['phone']) ? $_GET['phone'] : '' }}" placeholder="Phone">
                  <input class="form-control" type="text" name="email" value="{{ isset($_GET['email']) ? $_GET['email'] : '' }}" placeholder="Email">

                  <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i>
                      <span class="glyphicon glyphicon-search"></span>
                  </button>
              </div>
          </form>

          <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0;">
            <table id="table" class="table" style="width: 100%; table-layout: fixed;">
              <thead>
                <tr>
                  <th style="width:200px; text-align: center;">Name</th>
                  <th style="width:175px; text-align: center;">Email</th>
                  <th style="width:80px; text-align: center;">Phone</th>
                  <th style="width:80px; text-align: center;">Dob</th>
                	<th style="width:125px; text-align: center;"></th>
                </tr>
              </thead>
              <tbody>
              	@foreach($clients as $client)
                  <tr>
                    <td>{{$client->name}}</td>
                    <td>{{$client->email}}</td>
                    <td>{{$client->phone}}</td>
                    <td>{{$client->dob}}</td>
                    <td>
                      <div class="row" style="margin-right: 0; margin-left: 0;">
                        <div class="col-4" style="padding: 0;">
                          <a href="{{action('ClientController@show',$client->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                        </div>

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                          <a href="{{action('ClientController@edit',$client->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                        </div>

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                          <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="The client has been deleted." data-method="DELETE" data-action="{{action('ClientController@destroy',$client->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                        </div>

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }} hidden class="col-8 inactive" style="padding: 0;">
                          <button class="delete-alert btn btn-link" data-reload="0" data-table="#table" data-message1="Are you sure to activate this client?" data-message2="Activated" data-message3="Activated client." data-method="DELETE" data-action="{{action('ClientController@destroy',$client->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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
