@extends('layouts.layout')

@section('content')

<?php
  $menu= DB::select("select m.actions from menu_roles m, lookups l where m.lkp_role_id=".Auth::user()->role->id." and m.lkp_menu_id = l.id and l.key_value='User';");
?>

    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="card" id="card-section">

                <div class="input-group mb-2">

                    <a href="{{action('UserController@create')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
                </div>

                <form method="GET" action="{{action('UserController@index')}}">
                    <div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                        <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                        <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
                    </div>
                    <div class="input-group mb-5">
                        <select class="form-control" name="role">
                            <option value="">ALL ROLES</option>
                              @foreach($roles as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['role']) ? $_GET['role'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                              @endforeach
                        </select>

                        <input class="form-control" type="text" name="email_number" value="{{ isset($_GET['email_number']) ? $_GET['email_number'] : '' }}" placeholder="EMAIL/PHONE">

                        <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered">
                <table id="table" class="table tablesorter" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                            <th class="not-sortable" style="width:100px; text-align: center;">Name</th>
                            <th class="not-sortable" style="width:100px; text-align: center;">Email</th>
                            <th class="not-sortable" style="width:100px; text-align: center;">Number</th>
                            <th class="not-sortable" style="width:100px; text-align: center;">Role</th>
                            <th class="not-sortable" style="width:100px; text-align: center;">Client</th>
                            <th class="not-sortable" style="width:150px; text-align: center;">Date Work</th>
                            <th class="not-sortable" style="width:150px; text-align: center;">Date Birthday</th>
                            <th class="not-sortable" style="width:125px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->name}}</td>
                            <td>{{$r->email}}</td>
                            <td>{{$r->phone}}</td>
                            <td>{{$r->role->value}}</td>
                            @if($r->client_id != null)
                              <td>{{$r->client->name}}</td>
                            @else
                              <td></td>
                            @endif
                            <td>{{$r->date_work}}</td>
                            <td>{{$r->date_birth}}</td>
                            <td>

                                <div class="row" style="margin-right: 0; margin-left: 0;">
                                  <div class="col-4" style="padding: 0;">
                                    <a href="{{action('UserController@show',$r->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'R') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                                  </div>
                                  @if($r->name_image != null)
                                       <div class="col-4" style="padding: 0;">
                                        <a href="#" class="btn btn-link view_image {{str_contains($menu[0]->actions,'R') ? '' : 'disabled' }}" style="width:40px; margin: 0" data-toggle="modal" data-src="{{asset('/images/users')}}/{{$r->name_image}}" data-target="#exampleModalCenter"><i class="far fa-image"></i></a>
                                      </div>
                                  @endif
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <a href="{{action('UserController@edit',$r->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'U') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                                  </div>
                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                                    <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('UserController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                                  </div>

                                  <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }} class="col-8 inactive" style="padding: 0;">
                                    <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="0" data-table="#table" data-message1="Are you sure to activate this machine?" data-message2="Activated" data-message3="Activated machine." data-method="DELETE" data-action="{{action('UserController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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


<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="width: 300px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" style="padding: 0;">
          <img src="" id="view_image" alt="" style="width: 100%;">
        </div>
    </div>
  </div>
</div>

<script>
  
    $("body").on("click",".view_image",function(){
        $(document.getElementById("view_image")).attr("src",$(this).attr("data-src"));
    });

    window.onload = function() {
        
    };
</script>
@stop
