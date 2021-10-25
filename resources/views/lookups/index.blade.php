@extends('layouts.layout')

@section('content')

    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="card" id="card-section">
                
                <div class="input-group mb-2">
                    <a href="{{action('LookupController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>                    
                </div>

                <form method="GET" action="{{action('LookupController@index')}}">
                    <div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                        <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                        <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
                    </div>
                    <div class="input-group mb-5">
                        <input class="form-control" type="text" name="type" autofocus placeholder="Type" value="{{ isset($_GET['type']) ? $_GET['type'] : '' }}">

                        <input class="form-control" name="value" autofocus placeholder="Value" value="{{ isset($_GET['value']) ? $_GET['value'] : '' }}">

                        <button type="submit" class="btn btn-default" name="option"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                        	<th>Type</th>
                        	<th>Value</th>
                            <th style="width:125px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->p_value}}</td>                            
                            <td>{{$r->value}}</td>
                            <td>
                                <div class="row" style="margin-right: 0; margin-left: 0;">
                                    <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4" style="padding: 0;">
                                        <button data-type="{{$r->p_key_value}}" data-value="{{$r->value}}" data-href="{{action('LookupController@update',$r->id)}}" class="btn btn-link lookup_edit" style="width:40px; margin: 0;padding: 0;" data-toggle="modal" data-target="#exampleModalCenter"><i class="far fa-edit"></i></button>
                                    </div>

                                    <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                        <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('LookupController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                                    </div>

                                    <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }}  class="col-8 inactive" style="padding: 0;">
                                        <button class="delete-alert btn btn-link" data-reload="0" data-table="#table" data-message1="Are you sure to activate this configuration?" data-message2="Activated" data-message3="Activated configuration." data-method="DELETE" data-action="{{action('LookupController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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


    <!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Value</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <form method="POST" id="update-lookup">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="p_key_value" id="p_key_value" value="">
        @csrf

      <div class="modal-body">
        <input id="lookup-value" class="form-control" type="text" name="value" placeholder="Value">              
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form> 
    </div>
  </div>
</div>
@stop
