@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{action('PartController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>

          <form method="GET" action="{{ route('machines.index') }}">
              @csrf
              <div class="input-group mb-5">
                  <input class="form-control" type="text" name="type" autofocus placeholder="Type">

                  <input class="form-control" type="text" name="model" autofocus placeholder="Model">

                  <input class="form-control" type="text" name="status" autofocus placeholder="Status">

                  <input class="form-control" type="text" name="brand" autofocus placeholder="Brand">

                  <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i>
                      <span class="glyphicon glyphicon-search"></span>
                  </button>
              </div>
          </form>

          <div class=" table-responsive table-striped table-bordered" >
            <table id="example" class="table " style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                      <th style="width:50px; text-align: center;">#</th>
                      <th style="width:200px; text-align: center;">Brand</th>
                      <th style="width:200px; text-align: center;">Model</th>
                      <th style="width:200px; text-align: center;">Serial</th>
                      <th style="width:200px; text-align: center;">Price</th>
                      <th style="width:200px; text-align: center;">Weight</th>

                      <th style="width:200px; text-align: center;">Type</th>
                      <th style="width:200px; text-align: center;">Status</th>
                      <th style="width:200px; text-align: center;">Protocol</th>
                    	<th style="width:150px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                  <?php $s = 0; ?>
                	@foreach($parts as $part)
                    <?php $s++; ?>
                    <tr>
                      <td class="text-center">{{$s}}</td>
                      <td>{{$part->brand}}</td>
                      <td>{{$part->model}}</td>
                      <td>{{$part->serial}}</td>
                      <td>${{number_format($part->price,'2','.',',')}}</td>
                      <td>{{$part->weight}}</td>
                      <td>{{$part->type->value}}</td>
                      <td>{{$part->status->value}}</td>
                      <td>{{$part->protocol->value}}</td>
                      <td>
                        <div class="row" style="margin-right: 0; margin-left: 0;">
                          <div class="col-4" style="padding: 0;">
                            <a href="{{action('PartController@show',$part->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                          </div>
                          <div class="col-4" style="padding: 0;">
                            <a href="{{action('PartController@edit',$part->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                          </div>
                          <div class="col-4" style="padding: 0;">
                            <form class="" action="{{action('PartController@destroy',$part->id)}}" method="post">
                              @csrf
                              <input type="hidden" name="_method" value="delete">
                              <button class="btn btn-link" type="submit" onclick="return confirm('Are you sure to delete this piece?')" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                            </form>
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
