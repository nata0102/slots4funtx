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
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->p_value}}</td>                            
                            <td>{{$r->value}}</td>
                            <td>
                                <div class="row" style="margin-right: 0; margin-left: 0;">
                                  <div class="col-4" style="padding: 0;">
                                    <a href="{{action('LookupController@show',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                                  </div>
                                  <div class="col-4" style="padding: 0;">
                                    <a href="{{action('LookupController@edit',$r->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
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
