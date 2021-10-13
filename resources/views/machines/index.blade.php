@extends('layouts.app')

@section('content')
    
    
    <form method="GET" action="{{ route('machines.index') }}">
        @csrf
        <div class="input-group mb-5">            
            <input class="form-control" type="text" name="game" autofocus placeholder="Game Title">
        
            <input class="form-control" name="owner" autofocus placeholder="Owner Type (Mine/Service)">

            <input class="form-control" name="status" autofocus placeholder="Status">

            <input class="form-control" name="brand" autofocus placeholder="Brand">
        
            <button type="submit" class="btn btn-default" name="option" value="all">Search
                <span class="glyphicon glyphicon-search"></span>        
            </button>
        </div>
    </form>                        
              
    <table id="example" class="table table-striped table-bordered" style="width:100%;margin-top: 10px">
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
                <!--@if($r->active == 1)
                    <td><div class="form-check"><input style="display:block;margin:0 auto;" class="form-check-input" type="checkbox" value="" checked disabled></div></td>
                @else
                    <td><div class="form-check"><input style="display:block;margin:0 auto;" class="form-check-input" type="checkbox" value="" disabled></div></td>
                @endif-->
            </tr> 
            @endforeach           
        </tbody>
    </table>

@stop
