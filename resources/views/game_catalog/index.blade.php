@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <div class="input-group mb-2">
            <a href="{{action('GameCatalogController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
          </div>

          		<form method="GET" action="{{action('GameCatalogController@index')}}">
                	<div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                        <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                        <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
                    </div>
                    <div class="input-group mb-5">                        
                         <input class="form-control" name="name" autofocus placeholder="Software Name" value="{{ isset($_GET['name']) ? $_GET['name'] : '' }}">

                        <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                            <th style="width:100px; text-align: center;">Software Name</th>
                            <th style="width:100px; text-align: center;">Software License</th>
                            <th style="width:100px; text-align: center;">Description</th>
                            <th style="width:125px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>                            
                            <td>{{$r->name}}</td>
							<td>{{$r->license}}</td>
							<td>{{$r->description}}</td>
                            <td>
                                <div class="row" style="margin-right: 0; margin-left: 0;">
                                  <div class="col-4" style="padding: 0;">
                                    <a href="{{action('GameCatalogController@show',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                                  </div>
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <a href="{{action('GameCatalogController@edit',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                                  </div>
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('GameCatalogController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                                  </div>

                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }} class="col-8 inactive" style="padding: 0;">
                                    <button class="delete-alert btn btn-link" data-reload="0" data-table="#table" data-message1="Are you sure to activate this game?" data-message2="Activated" data-message3="Activated game." data-method="DELETE" data-action="{{action('GameCatalogController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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