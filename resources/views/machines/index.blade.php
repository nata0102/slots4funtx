@extends('layouts.layout')

@section('content')
    
    

    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="card" id="card-section">
                <a href="{{action('MachineController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
                <form method="GET" action="{{ route('machines.index') }}">
                    <div class="input-group mb-5">            
                        <input class="form-control" type="text" name="game" autofocus placeholder="Game Title">
                    
                        <input class="form-control" name="owner" autofocus placeholder="Owner Type (Mine/Service)">

                        <input class="form-control" name="status" autofocus placeholder="Status">

                        <input class="form-control" name="brand" autofocus placeholder="Brand">
                    
                        <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>        
                        </button>
                    </div>
                </form>    

                <div class=" table-responsive table-striped table-bordered" >
                <table id="example" class="table " style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                        	<th>Game Title</th>
                        	<th>Owner Type</th>    
                        	<th>Serial</th>
                        	<th>Inventory</th>
                            <th>Client</th>
                            <th>Business</th>
                            <th>Status</th>
                            <th>Machine Brand</th>
                            <th>Date Sale</th>
                            <th></th>
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
                                  <div class="col-4" style="padding: 0;">
                                    <a href="{{action('MachineController@edit',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                                  </div>
                                  <div class="col-4" style="padding: 0;">
                                    <button class="delete-alert btn btn-link" type="button" data-action="{{action('MachineController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
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