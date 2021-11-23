@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <div class="input-group mb-2">
            <a href="{{action('PartController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
            <p style="margin-left: 10px;padding-top: 5px;font-weight: bold;">Assign to Machine</p>
            <a href="{{action('PartController@createByRank')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;margin-left: 250px;"><i class="fas fa-plus"></i></a>
                    <p style="margin-left: 10px;padding-top: 5px;font-weight: bold;">By Rank</p>
          </div>

          <form method="GET" action="{{action('PartController@index')}}">
              <div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
              </div>
              <div class="input-group mb-5">
                  <select onchange="fillBrand(this, {{$brands}})" id="parts_type" class="form-control" name="type">
                      <option value="" >-- Select Type --</option>
                      @foreach($types as $tp)
                          <option value="{{$tp->id}}" {{isset($_GET['type']) ? $_GET['type'] == $tp->id ?   'selected' : '' : ''}}>{{$tp->value}}</option>
                      @endforeach                              
                  </select>
                  <select class="form-control" name="brand" id="parts_brands" data-old="{{old('brand')}}">
                      <option value="">-- Select Brand --</option>
                        <!--@foreach($brands as $tp)
                          <option value="{{$tp->id}}"  {{isset($_GET['brand']) ? $_GET['brand'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->brand}} {{$tp->model}} {{$tp->weight}}</option>
                        @endforeach-->
                  </select>

                  <select class="form-control" name="status" >
                      <option value="">-- Select Status --</option>
                        @foreach($status as $tp)
                          <option value="{{$tp->id}}"  {{ isset($_GET['status']) ? $_GET['status'] == $tp->id ? 'selected' : '' : '' }}>{{$tp->value}}</option>
                        @endforeach
                  </select>

                  <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i>
                      <span class="glyphicon glyphicon-search"></span>
                  </button>
              </div>
          </form>

          <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0;">
            <table id="table" class="table" style="width: 100%; table-layout: fixed;">
                <thead>
                    <tr>
                      <th style="width:100px; text-align: center;">Type</th>
                      <th style="width:100px; text-align: center;">Brand-Model</th>
                      <th style="width:150px; text-align: center;">Protocol</th>
                      <th style="width:100px; text-align: center;">Serial</th>
                      <th style="width:85px; text-align: center;">Price</th>
                      <th style="width:135px; text-align: center;">Status</th>
                      <th style="width:175px; text-align: center;">Machine</th>
                    	<th style="width:125px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($parts as $part)
                    <tr>
                      @if($part->type != NULL)
                        <td>{{$part->type->value}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->brand_id != NULL)
                        <td>{{$part->brand->brand}} {{$part->brand->model}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->protocol != NULL)
                        <td>{{$part->protocol->value}}</td>
                      @else
                        <td></td>
                      @endif
                      <td>{{$part->serial}}</td>
                      <td>${{number_format($part->price,'2','.',',')}}</td>
                      @if($part->status != NULL)
                        <td>{{$part->status->value}}</td>
                      @else
                        <td></td>
                      @endif                      
                      @if($part->machine_id != null)
                        @if($part->machine->machine_brand_id != null)
                          <td>{{$part->machine_id}} - {{$part->machine->brand->brand}} {{$part->machine->brand->model}} - {{$part->machine->serial}}</td>
                        @else
                          <td></td>
                        @endif
                      @else
                        <td></td>
                      @endif
                      <td>
                        <div class="row" style="margin-right: 0; margin-left: 0;">
                          <div class="col-4" style="padding: 0;">
                            <a href="{{action('PartController@show',$part->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                          </div>

                          <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                            <a href="{{action('PartController@edit',$part->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                          </div>

                          <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                            <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('PartController@destroy',$part->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                          </div>

                          <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }}  class="col-8 inactive" style="padding: 0;">
                            <button class="delete-alert btn btn-link" data-reload="0" data-table="#table" data-message1="Are you sure to activate this part?" data-message2="Activated" data-message3="Activated part." data-method="DELETE" data-action="{{action('PartController@destroy',$part->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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

<script>
  function fillBrand(type, brands){
    var select = document.getElementById("parts_brands");
    var length = select.options.length;
    for (i = length-1; i >= 0; i--) 
      select.options[i] = null;

    var option = document.createElement("option");
    option.value = ""
    option.text = "-- Select Brand --";
    option.select = 'selected';
    select.appendChild(option);

    for(var i=0;i < brands.length; i++){      
      if(type.value == brands[i].lkp_part_id){
        var option = document.createElement("option");
        option.value = brands[i].id;
        option.text = brands[i].brand+" "+brands[i].model;
        select.appendChild(option);
      }
    }
  }

  window.onload = function() { 
     let type = document.getElementById("parts_type");
     if(type.value != ''){
        let select = document.getElementById("parts_brands");
        for(var i=0; i < {!!$brands!!}.length; i++){ 
          if(type.value == {!!$brands!!}[i].lkp_part_id){
              var option = document.createElement("option");
              option.value = {!!$brands!!}[i].id;
              option.text = {!!$brands!!}[i].brand+" "+{!!$brands!!}[i].model;
              @if(isset($_GET['brand']))
                if({!!$_GET['brand']!!} == {!!$brands!!}[i].id)
                    option.selected = 'selected';
              @endif
              select.appendChild(option);
          }
        } 
     }
  }; 
</script>
@stop
