@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{action('PartController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>

          <form method="GET" action="{{action('PartController@index')}}">
              <div class="input-group mb-5">
                  <input class="form-control" type="text" name="type" value="{{ isset($_GET['type']) ? $_GET['type'] : '' }}" placeholder="Type">
                  <input class="form-control" type="text" name="model" value="{{ isset($_GET['model']) ? $_GET['model'] : '' }}" placeholder="Model">
                  <input class="form-control" type="text" name="status" value="{{ isset($_GET['status']) ? $_GET['status'] : '' }}" placeholder="Status">
                  <input class="form-control" type="text" name="brand" value="{{ isset($_GET['brand']) ? $_GET['brand'] : '' }}" placeholder="Brand">

                  <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i>
                      <span class="glyphicon glyphicon-search"></span>
                  </button>
              </div>
          </form>

          <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0;">
            <table id="table" class="table" style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                      <th style="width:100px; text-align: center;">Brand</th>
                      <th style="width:100px; text-align: center;">Model</th>
                      <th style="width:100px; text-align: center;">Serial</th>
                      <th style="width:85px; text-align: center;">Price</th>
                      <th style="width:70px; text-align: center;">Weight</th>
                      <th style="width:100px; text-align: center;">Type</th>
                      <th style="width:135px; text-align: center;">Status</th>
                      <th style="width:150px; text-align: center;">Protocol</th>
                      <th style="width:175px; text-align: center;">Machine</th>
                    	<th style="width:125px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($parts as $part)
                    <tr id="row-{{$part->id}}">
                      <td>{{$part->brand}}</td>
                      <td>{{$part->model}}</td>
                      <td>{{$part->serial}}</td>
                      <td>${{number_format($part->price,'2','.',',')}}</td>
                      <td>{{$part->weight}}</td>
                      @if($part->type != NULL)
                        <td>{{$part->type->value}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->status != NULL)
                        <td>{{$part->status->value}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->protocol != NULL)
                        <td>{{$part->protocol->value}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->machine_id != NULL)
                        <td>{{$part->machine->game_title}} - {{$part->machine->serial}}</td>
                      @else
                        <td></td>
                      @endif
                      <td>
                        <div class="row" style="margin-right: 0; margin-left: 0;">
                          <div class="col-4" style="padding: 0;">
                            <a href="{{action('PartController@show',$part->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                          </div>
                          <div class="col-4" style="padding: 0;">
                            <a href="{{action('PartController@edit',$part->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                          </div>
                          <div class="col-4" style="padding: 0;">
                            <button class="delete-alert btn btn-link" type="button" data-row="#row-{{$part->id}}" data-table="#table" data-action="{{action('PartController@destroy',$part->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
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
