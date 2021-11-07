@extends('layouts.layout')

@section('content')

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="card" id="card-section">
        
                <div class="input-group mb-2">
                    <a href="{{action('PercentagePriceController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>                   
                </div>

                <form method="GET" action="{{action('PercentagePriceController@index')}}">
                    <div class="input-group mb-5">

                        <select class="form-control" name="type">
                            <option value="">-- Select Type --</option>
                              @foreach($types as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['type']) ? $_GET['type'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                              @endforeach
                        </select>  

                        <select class="form-control" name="periodicity">
                            <option value="">-- Select Periodicity --</option>
                              @foreach($periodicities as $tp)
                                <option value="{{$tp->id}}"  {{ isset($_GET['periodicity']) ? $_GET['periodicity'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                              @endforeach
                        </select> 

                        <input class="form-control" name="machine" autofocus placeholder="Machine" value="{{ isset($_GET['machine']) ? $_GET['machine'] : '' }}">                       

                        <button type="submit" class="btn btn-default" name="option"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                            <th>Type</th>
                        	<th>Machine</th>                        	
                            <th>Percentage/Amount</th>
                            <th>Payment Periodicity</th>
                            <th>Payday</th>
                            <th>Start Payment</th>
                            <th style="width:125px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->type}}</td>                            
                            <td>{{$r->machine_name}}</td>
                            <td>{{$r->amount}}</td>
                            <td>{{$r->type_periodicity}}</td>
                            <td>{{$r->payday}}</td>
                            <td>{{$r->start_payment}}</td>
                            <td>
                                <div class="row" style="margin-right: 0; margin-left: 0;">
                                  <div class="col-4 active" style="padding: 0;">
                                    <a href="{{action('PercentagePriceController@edit',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                                  </div>
                                  <div class="col-4 active" style="padding: 0;">
                                    <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('PercentagePriceController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
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
